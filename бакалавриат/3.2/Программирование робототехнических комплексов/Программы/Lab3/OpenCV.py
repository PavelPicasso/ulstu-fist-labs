import vrep
import sys
import time
import numpy as np
import cv2

class Simulation:
    def __init__(self):
        vrep.simxFinish(-1)
        self.clientId = vrep.simxStart('127.0.0.1', 19997, True, True, 5000, 5)
        self.checkConnection()

    def startSimulation(self):
        print("Press \"Start simulation in V-REP\"")

        while vrep.simxGetConnectionId(self.clientId) != -1:
            res, visionSensor = vrep.simxGetObjectHandle(self.clientId, 'Vision_sensor', vrep.simx_opmode_oneshot_wait)
            err, resolution, image = vrep.simxGetVisionSensorImage(self.clientId, visionSensor, 0, vrep.simx_opmode_streaming)

            if err == vrep.simx_return_ok:
                img = np.array(image, dtype=np.uint8)
                img.resize([resolution[1], resolution[0], 3])
                img = cv2.flip(img, 0)

                hsvLowerBorderColor = np.array([82, 25, 0])
                hsvUpperBorderColor = np.array([161, 255, 255])

                hsvImg = cv2.cvtColor(img, cv2.COLOR_RGB2HSV)# меняем цветовую модель с BGR на HSV
                thresh = cv2.inRange(hsvImg, hsvLowerBorderColor, hsvUpperBorderColor)# применяем цветовой фильтр
                contours, hierarchy = cv2.findContours(thresh.copy(), cv2.RETR_TREE, cv2.CHAIN_APPROX_SIMPLE)
                # перебираем все найденные контуры в цикле
                for con in contours:
                    rect = cv2.minAreaRect(con)# пытаемся вписать прямоугольник
                    box = cv2.boxPoints(rect)# поиск четырех вершин прямоугольника
                    box = np.int0(box)# округление координат

                    area = int(rect[1][0] * rect[1][1])

                    if area > 120:
                        peri = cv2.arcLength(con, True)
                        approx = cv2.approxPolyDP(con, 0.1 * peri, True)

                        if len(approx) == 4 or len(approx) == 5:
                            (x, y, w, h) = cv2.boundingRect(approx)
                            cv2.drawContours(hsvImg, [box], 0, (255, 255, 250), 2)# рисуем прямоугольник

                bgrImg = cv2.cvtColor(hsvImg, cv2.COLOR_HSV2BGR)
                cv2.imshow('vision sensor', bgrImg)

                if cv2.waitKey(1) & 0xFF == ord('q'):
                    break
	
        vrep.simxFinish(self.clientId)
	
    def checkConnection(self):
        if self.clientId != -1:
            print("Connected to remote server")
        else:
            print('Connection not successful')
            sys.exit('Could not connect')

			
if __name__ == '__main__':
    s = Simulation()
    s.startSimulation()
    cv2.destroyAllWindows()

#Аппроксимация контуров основана на предположении, что кривая может быть аппроксимирована серией коротких отрезков. Это приводит к результирующей аппроксимированной кривой, которая состоит из подмножества точек, которые были определены исходным cruve.