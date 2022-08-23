import shutil
import os

def files(path):
    for file in os.listdir(path):
        if os.path.isfile(os.path.join(path, file)):
            yield file

numb = 2019
for i in range(10000):
    shutil.copy2(f'09.03.01_02-{numb}.plx', f'files/09.03.01_02-{numb + i}.plx')

numb = 2019
for fileName in files('./files'):
    with open('./files/' + fileName, 'r', encoding='utf-8') as file:
        text = file.read()

        l = text.find('LastName')
        r = text.find('LastWrite')
        
        with open('./files/' + fileName, 'w', encoding='utf-8') as newFile:
            newFile.write(text[ : l])
            newFile.write("LastName=\"")
            newFile.write(f"09.03.01_02-{numb}.plx\" ")
            newFile.write(text[r : ])
    numb += 1