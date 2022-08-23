import vrep
import sys
import time
import numpy as np
import pylab as pl
import math

times = []
errors = []
us = []
purpose = 0.6
v = 1

error_old = 0
i_min = -0.2
i_max = 0.2
i_sum = 0

def check_error_code(error_code, message, need_exit = False):
    if error_code != vrep.simx_return_ok:
        print("ERROR: Code {}. {}".format(error_code, message))
        if need_exit:
            sys.exit()
        return False
    return True

def set_motor_speed(client_id, left_speed, right_speed):
    e = vrep.simxSetJointTargetVelocity(client_id, left_motor_handle, left_speed, vrep.simx_opmode_oneshot_wait)
    check_error_code(e, "SetJointTargetVelocity for left motor got error code")
    e = vrep.simxSetJointTargetVelocity(client_id, right_motor_handle, right_speed, vrep.simx_opmode_oneshot_wait)
    check_error_code(e, "SetJointTargetVelocity for right motor got error code")
    print("Motor speed set to {} {}".format(left_speed, right_speed))

def controller(error):
    up = error
    global error_old, i_min, i_max, i_sum
    i_sum += error
    i_sum = max(i_sum, i_min)
    i_sum = min(i_sum, i_max)
    ui = 0.005 * i_sum
    ud = 0.05 * (error - error_old)
    error_old = error
    return up + ud + ui

def start_simulation(client_id, proximity_handle, proximity_handle2):
    #set_motor_speed(client_id, 1, 1)

    t = time.time()
    while vrep.simxGetConnectionId(client_id) != -1:
        (errorCode, detectionState, detectedPoint, detectedObjectHandle,
         detectedSurfaceNormalVector) = vrep.simxReadProximitySensor(client_id, proximity_handle,
                                                                     vrep.simx_opmode_streaming)
        right = detectedPoint[2] if detectionState == True else 1e6

        (errorCode, detectionState2, detectedPoint2, detectedObjectHandle,
         detectedSurfaceNormalVector) = vrep.simxReadProximitySensor(client_id, proximity_handle2,
                                                                     vrep.simx_opmode_streaming)
        front = detectedPoint2[2] - 0.6 if detectionState2 == True else 1e6
        dist = min(right, front)
        error = dist - purpose
        u = controller(error)

        if error > 0:
            errorCode = vrep.simxSetJointTargetVelocity(client_id, left_motor_handle, v + u,
                                            vrep.simx_opmode_streaming)
            errorCode = vrep.simxSetJointTargetVelocity(client_id, right_motor_handle, v - u, vrep.simx_opmode_streaming)
            print("r ", error, " ", v + u, " ", v - u, " ", u)
        elif error < 0:
            errorCode = vrep.simxSetJointTargetVelocity(client_id, left_motor_handle, v + u,
                                                                             vrep.simx_opmode_streaming)
            errorCode = vrep.simxSetJointTargetVelocity(client_id, right_motor_handle, v - u, vrep.simx_opmode_streaming)
            print("l ", error, " ", v + u, " ", v - u, " ", u)

        if abs(error) < 1 and abs(u) < 6:
            times.append(time.time())
            errors.append(error)
            us.append(u)

    print('Simulation finished')
    pl.plot(times, errors)
    pl.xlabel('Время')
    pl.ylabel('Ошибка')
    pl.savefig('error.png')
    pl.close()
    pl.plot(times, us)
    pl.xlabel('Время')
    pl.ylabel('Управляющее воздействие')
    pl.savefig('u.png')
    exit()

print('Program started')
vrep.simxFinish(-1)
clientID = vrep.simxStart('127.0.0.1', 19997, True, True, 5000, 5)
if clientID != -1:
    print("Connected to remote server")
else:
    print('Connection not successful')
    sys.exit('Could not connect')


errorCode, left_motor_handle = vrep.simxGetObjectHandle(clientID, 'Pioneer_p3dx_leftMotor', vrep.simx_opmode_oneshot_wait)
check_error_code(errorCode, "Can not find left motor", True)
errorCode, right_motor_handle = vrep.simxGetObjectHandle(clientID,'Pioneer_p3dx_rightMotor', vrep.simx_opmode_oneshot_wait)
check_error_code(errorCode, "Can not find right motor", True)

errorCode, sensor1 = vrep.simxGetObjectHandle(clientID, 'Proximity_sensor', vrep.simx_opmode_oneshot_wait)
check_error_code(errorCode, "Can not find proximity sensor", True)

errorCode, sensor2 = vrep.simxGetObjectHandle(clientID, 'Proximity_sensor2', vrep.simx_opmode_oneshot_wait)
check_error_code(errorCode, "Can not find proximity sensor2", True)

errorCode, detectionState, detectedPoint, detectedObjectHandle, detectedSurfaceNormalVector = vrep.simxReadProximitySensor(clientID, sensor1, vrep.simx_opmode_streaming)

errorCode, detectionState, detectedPoint, detectedObjectHandle, detectedSurfaceNormalVector = vrep.simxReadProximitySensor(clientID, sensor2, vrep.simx_opmode_streaming)


sec, msec = vrep.simxGetPingTime(clientID)
print("Ping time: %f" % (sec + msec / 1000.0))
print("Initialization finished")


start_simulation(clientID, sensor1, sensor2)