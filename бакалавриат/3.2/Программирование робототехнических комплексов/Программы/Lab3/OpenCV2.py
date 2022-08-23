import vrep
import sys
import cv2
# Importing the Opencv Library
import numpy as np
# Importing NumPy,which is the fundamental package for scientific computing with #Python
vrep.simxFinish(-1)
clientId = vrep.simxStart('127.0.0.1', 19997, True, True, 5000, 5)
if clientId != -1:
    print("Connected to remote server")
else:
    print('Connection not successful')
    sys.exit('Could not connect')

print("Press \"Start simulation in V-REP\"")

while vrep.simxGetConnectionId(clientId) != -1:
    res, visionSensor = vrep.simxGetObjectHandle(clientId, 'Vision_sensor', vrep.simx_opmode_oneshot_wait)
    err, resolution, image = vrep.simxGetVisionSensorImage(clientId, visionSensor, 0, vrep.simx_opmode_streaming)

    if err == vrep.simx_return_ok:
        img = np.array(image, dtype=np.uint8)
        img.resize([resolution[1], resolution[0], 3])
        img = cv2.flip(img, 0)
        cv2.imshow('vision sensor', img)
        # RGB to Gray scale conversion
        img_gray = cv2.cvtColor(img,cv2.COLOR_RGB2GRAY)
        noise_removal = cv2.bilateralFilter(img_gray,9,75,75)
        ret,thresh_image = cv2.threshold(noise_removal,0,255,cv2.THRESH_OTSU)
        canny_image = cv2.Canny(thresh_image,250,255)
        canny_image = cv2.convertScaleAbs(canny_image)

        # dilation to strengthen the edges
        kernel = np.ones((3,3), np.uint8)
        # Creating the kernel for dilation
        dilated_image = cv2.dilate(canny_image,kernel,iterations=1)
        # Displaying Image
        contours, h = cv2.findContours(dilated_image, 1, 2)
        contours= sorted(contours, key = cv2.contourArea, reverse = True)[:1]
        pt = (180, 3 * img.shape[0] // 4)
        for cnt in contours:
            approx = cv2.approxPolyDP(cnt,0.01*cv2.arcLength(cnt,True),True)
            if len(approx) == 8:
                cv2.drawContours(img, [cnt], -1, (255, 255, 250), 3)
                corners = cv2.goodFeaturesToTrack(thresh_image, 6, 0.06, 25)
                corners = np.float32(corners)
                for item in corners:
                    x, y = item[0]
                    cv2.circle(img, (x, y), 10, 255, -1)
            cv2.imshow('vision sensor', img)

            if cv2.waitKey(1) & 0xFF == ord('q'):
                break
vrep.simxFinish(clientId)

cv2.waitKey()