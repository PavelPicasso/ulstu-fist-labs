import os
def files(path):
    for file in os.listdir(path):
        if os.path.isfile(os.path.join(path, file)):
            yield file

for fileName in files('./files'):
    with open('./files/' + fileName, 'r', encoding='utf-8') as file:
        text = file.read()
        
        with open('./files/' + fileName, 'w', encoding='utf-8') as newFile:
            newFile.write('<?xml version="1.0" encoding="utf-8"?>\n')
            newFile.write(text[40 : ])