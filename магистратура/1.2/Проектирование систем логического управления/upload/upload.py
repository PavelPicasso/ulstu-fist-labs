import xml.etree.ElementTree as ET
import configparser
import psycopg2
import datetime
import os
import multiprocessing
import time

request = {}

def chunks(lst, chunk_count):
    listChunk = []
    chunk_size = len(lst) // chunk_count
    for i in range(0, chunk_size * chunk_count, chunk_size):
        listChunk.append(lst[i:i+chunk_size])
    
    i = 0
    for j in range(chunk_size * chunk_count, len(lst)):
        listChunk[i].append(lst[j])
        i += 1

    return listChunk

def files(path):
    for file in os.listdir(path):
        if os.path.isfile(os.path.join(path, file)):
            yield file

def connect(conf):
    conn = None
    try:
        print("Connecting to the PostgreSQL database...")
        conn = psycopg2.connect(
            user = conf['DATABASE']['USERNAME'],
            password = conf['DATABASE']['PASSWORD'],
            host = conf['DATABASE']['HOST'],
            port = conf['DATABASE']['PORT'],
            database = conf['DATABASE']['DB']
        )
    except (Exception, psycopg2.DatabaseError) as error:
        raise SystemExit(error)
    print("Connection successful")
    return conn


def insert(connection, table, fields, value, file):
    try:
        cursor = connection.cursor()

        insertQuery = f"INSERT INTO {table} ({fields}) VALUES ({value})"

        # with open("query.sql", "a", encoding="utf-8") as query:
        #     query.write(f"{insertQuery};\n")

        cursor.execute(insertQuery)
        connection.commit()

        cursor.close()

    except (Exception) as error:
        file.write(f"Запрос: {insertQuery} \n")
        file.write(f"Ошибка при работе с PostgreSQL: {error} \n")
        raise SystemExit("Ошибка при работе с PostgreSQL", error)

def realName(elem):
    lbracket = elem.find('{')
    rbracket = elem.find('}')

    if(lbracket != -1 and rbracket != -1):
        return elem[rbracket + 1 : ]
    else:
        return elem

def main(calc, proc, config, listFiles):    
    print(f"Запускаем поток № {proc}")
    connection = connect(config)
    
    with open("log", "a", encoding="utf-8") as file:
        for fileName in listFiles:

            tree = ET.parse("./genfiles/files/" + fileName)
            tagList = []

            for elem in tree.iter():
                tagList.append(elem.tag)

            tagList = list(set(tagList))

            for elem in tagList:
                request[realName(elem)] = []

            for tag in tagList:
                for item in tree.iter(tag):
                    tableName = realName(item.tag)

                    tempColumns = []
                    tempValues = []
                    for key in item.attrib:
                        tempColumns.append(f"\"{realName(key)}\"")
                        tempValues.append(f"'{item.attrib[key]}'")
                    
                    if("Документ" != tableName):
                        tempColumns.append(f"\"ИмяРодительскогоДокумента\"")
                        tempValues.append(f"'{fileName}'")
                    
                    request[tableName].append({"columns": tempColumns, "values": tempValues})

            for table in request:
                for data in request[table]:
                    insert(connection, f"\"{table}\"", ', '.join(data['columns']), ', '.join(data['values']), file)   
            file.write(f"Записи успешно добавлены ​​в базу данных '{config['DATABASE']['DB']}' из файла {fileName} \n")

        connection.close()
        print("Соединение с PostgreSQL закрыто")

        file.close()
        request.clear()

        print(f"{calc} циклов вычислений закончены, по файлам {listFiles}. Процессор № {proc}")

def processesed(procs, config, listFiles):
    # procs - количество ядер
    
    processes = []
    
    # делим вычисления на количество ядер
    for proc in range(procs):
        p = multiprocessing.Process(target=main, args=(len(listFiles[proc]), proc, config, listFiles[proc]))
        processes.append(p)
        p.start()

    # Ждем, пока все ядра завершат свою работу.
    for p in processes:
        p.join()

if __name__ == "__main__":
    with open("log", "a", encoding="utf-8") as file:
        file.write(f"\n\n{str(datetime.datetime.now())}\n")
        
        config = configparser.ConfigParser()
        config.read("conf.ini")
        connection = connect(config)

        cursor = connection.cursor()
        
        nameDocumentBd = []
        filesDirectory = list(files("./genfiles/files"))
        for fileName in filesDirectory:
            postgreSQL_select_Query = f"select * from Документ where \"LastName\" = '{fileName}'"
            cursor.execute(postgreSQL_select_Query)
            document_records = cursor.fetchall()

            if(document_records):
                nameDocumentBd.append(document_records[0][7])

        cursor.close()
        connection.close()
        
        listFiles = list(set(filesDirectory) - set(nameDocumentBd))
        if(listFiles):
            file.write(f"Запись будет произведена по файлам {str(listFiles)} \nВсего файлов {len(listFiles)}\n\n\n")
            print(f"Запись будет произведена по файлам {str(listFiles)}\nВсего файлов {len(listFiles)}")
        else:
            file.write("Новых файлов на запись нету\n")
            raise SystemExit("Новых файлов на запись нету")
        
        file.close()

    n_proc = multiprocessing.cpu_count()
    listFilesChunks = chunks(listFiles, n_proc)

    start = time.time()
    
    processesed(n_proc, config, listFilesChunks)
    
    end = time.time()

    print(f"Всего {n_proc} ядер в процессоре")
    print(f"Итого: ", end - start)
