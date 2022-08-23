import xml.etree.ElementTree as ET
import configparser
import psycopg2
import datetime
import sys
import os
import multiprocessing
import time

def connect(conf):
    conn = None
    try:
        print('Connecting to the PostgreSQL database...')
        conn = psycopg2.connect(
            user = conf["DATABASE"]["USERNAME"],
            password = conf["DATABASE"]["PASSWORD"],
            host = conf["DATABASE"]["HOST"],
            port = conf["DATABASE"]["PORT"],
            database = conf["DATABASE"]["DB"]
        )
    except (Exception, psycopg2.DatabaseError) as error:
        print(error)
        sys.exit(1) 
    print('Connection successful')
    return conn


def insert(connection, table, fields, value, file):
    try:
        cursor = connection.cursor()

        insertQuery = f'INSERT INTO {table} ({fields}) VALUES ({value})'
        cursor.execute(insertQuery)
        connection.commit()

        cursor.close()

    except (Exception) as error:
        file.write(f'Запрос: {insertQuery} \n')
        file.write(f'Ошибка при работе с PostgreSQL: {error} \n')
        raise SystemExit("Ошибка при работе с PostgreSQL", error)

def realName(elem):
    lbracket = elem.find('{')
    rbracket = elem.find('}')

    if(lbracket != -1 and rbracket != -1):
        return elem[rbracket + 1 : ]
    else:
        return elem

def filesDirectory(path):
    for file in os.listdir(path):
        if os.path.isfile(os.path.join(path, file)):
            yield file

def checkingData(query, name, fileName):
    try:
        cursor = connection.cursor()

        cursor.execute(query)
        result = cursor.fetchall()

        cursor.close()

        if(result):
            return 1
        else:
            file.write(f'Поля \'{name}\' с таким именем не существует в БД | файл {fileName}\n')
            return 0

    except (Exception) as error:
        file.write(f'Ошибка при работе с PostgreSQL: {error} \n')
        raise SystemExit("Ошибка при работе с PostgreSQL", error)

def main(connection, files, file):    
    for fileName in files:

        tree = ET.parse('./files/' + fileName)
        tagList = []

        for elem in tree.iter():
            tagList.append(elem.tag)

        tagList = list(set(tagList))
        tableName = realName(tree.getroot().tag)

        for tag in tagList:
            for item in tree.iter(tag):
                tableName = realName(item.tag)

                query = f"""
                    SELECT relname, pg_class.relkind as relkind FROM pg_class, pg_namespace
                    WHERE pg_class.relnamespace=pg_namespace.oid
                    AND pg_class.relkind IN ('v', 'r')
                    AND pg_namespace.nspname='public'
                    AND relname = '{tableName}';
                """
                if(checkingData(query, tableName, fileName) == 1):
                    columns = []
                    values = []
                    for key in item.attrib:
                        query = f"""
                            Select Column_Name
                            From INFORMATION_SCHEMA.COLUMNS
                            Where Table_Name = '{tableName}'
                            And Column_Name = '{realName(key)}'
                        """
                        
                        if(checkingData(query, realName(key), fileName) == 1):
                            columns.append(f'"{realName(key)}"')
                            values.append(f'\'{item.attrib[key]}\'')
                    
                    if('Документ' != tableName):
                        columns.append(f'"ИмяРодительскогоДокумента"')
                        values.append(f'\'fileName\'')
                    insert(connection, f'"{tableName}"', ', '.join(columns), ', '.join(values), file)
            file.write(f'Записи успешно добавлены ​​в таблицу {tableName} из файла {fileName} \n')

    if connection:
        connection.close()
        print('Соединение с PostgreSQL закрыто')

if __name__ == "__main__":
    with open('log', 'a', encoding='utf-8') as file:
        file.write(f'\n\n{str(datetime.datetime.now())}\n')
        
        config = configparser.ConfigParser()
        config.read("conf.ini")
        connection = connect(config)

        cursor = connection.cursor()
        postgreSQL_select_Query = "select * from Документ"

        cursor.execute(postgreSQL_select_Query)
        document_records = cursor.fetchall()
        cursor.close()

        nameDocumentBd = []
        for item in document_records:
            nameDocumentBd.append(item[7])
        
        listFiles = list(set(filesDirectory('./files')) - set(nameDocumentBd))
        
        if(listFiles):
            file.write(f'Запись будет произведена по файлам {str(listFiles)} \n')
            print(f'Запись будет произведена по файлам {str(listFiles)}')
        else:
            file.write('Новых файлов на запись нету\n')
            print('Новых файлов на запись нету')
        
        start = time.time()
        main(connection, listFiles, file)
        end = time.time()
        print(end - start)