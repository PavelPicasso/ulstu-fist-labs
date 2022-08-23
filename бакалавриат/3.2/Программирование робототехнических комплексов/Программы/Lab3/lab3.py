MAP_SIZE_PIXELS = 1000
MAP_SIZE_METERS = 40

import vrep
import sys
import time
import numpy as np
import math
import matplotlib.pyplot as plt
import cv2


class Robot:
    client_id = -1
    e = 0
    prev_e = 0
    iSum = 0
    initial_speed = 0.6
    maintained_dist_right = 0.6
    prev_alpha = 0
    update_time = 0.01

    def __init__(self):
        vrep.simxFinish(-1)
        self.client_id = vrep.simxStart('127.0.0.1', 19999, True, True, 5000, 5)
        if self.client_id != -1:
            print("Connected to remote server")
        else:
            print('Connection not successful')
            sys.exit('Could not connect')

        self.left_speed, self.right_speed = self.initial_speed, self.initial_speed

        error_code, self.left_motor_handle = vrep.simxGetObjectHandle(self.client_id, 'Pioneer_p3dx_leftMotor',
                                                                      vrep.simx_opmode_oneshot_wait)
        self.check_on_error(error_code, "Couldn't find left motor!", True)

        error_code, self.right_motor_handle = vrep.simxGetObjectHandle(self.client_id, 'Pioneer_p3dx_rightMotor',
                                                                       vrep.simx_opmode_oneshot_wait)
        self.check_on_error(error_code, "Couldn't find right motor!", True)

        error_code, self.laser_left = vrep.simxGetObjectHandle(self.client_id, 'Pr_sensor_left',
                                                               vrep.simx_opmode_oneshot_wait)
        self.check_on_error(error_code, "Couldn't find left laser!", True)
        error_code, self.laser_right = vrep.simxGetObjectHandle(self.client_id, 'Pr_sensor_right',
                                                                vrep.simx_opmode_oneshot_wait)
        self.check_on_error(error_code, "Couldn't find right laser!", True)
        error_code, self.laser_front = vrep.simxGetObjectHandle(self.client_id, 'Pr_sensor_front',
                                                                vrep.simx_opmode_oneshot_wait)
        self.check_on_error(error_code, "Couldn't find front laser!", True)
        error_code, self.laser_middle = vrep.simxGetObjectHandle(self.client_id, 'Pr_sensor_middle',
                                                                 vrep.simx_opmode_oneshot_wait)
        self.check_on_error(error_code, "Couldn't find middle laser!", True)
        error_code, self.cuboid = vrep.simxGetObjectHandle(self.client_id, 'Cuboid',
                                                           vrep.simx_opmode_oneshot_wait)
        self.check_on_error(error_code, "Couldn't find cuboid!", True)

        self.relative_object = -1

        # looking for the robot
        error_code, self.robot_handle = vrep.simxGetObjectHandle(self.client_id, 'Pioneer_p3dx',
                                                                 vrep.simx_opmode_oneshot_wait)
        self.check_on_error(error_code, "Couldn't find left robot!", True)

        self.prev_position = vrep.simxGetObjectPosition(self.client_id, self.robot_handle, self.cuboid,
                                                        vrep.simx_opmode_streaming)
        self.prev_orientation = vrep.simxGetObjectOrientation(self.client_id, self.robot_handle, self.relative_object,
                                                              vrep.simx_opmode_streaming)

        self.prev_position = \
        vrep.simxGetObjectPosition(self.client_id, self.robot_handle, self.cuboid, vrep.simx_opmode_buffer)[1]
        self.prev_orientation = \
        vrep.simxGetObjectOrientation(self.client_id, self.robot_handle, self.relative_object, vrep.simx_opmode_buffer)[
            1]

        self.prev_time = time.time()

        sec, msec = vrep.simxGetPingTime(self.client_id)
        print("Ping time: %f" % (sec + msec / 1000.0))
        print('Initialization is finished.')

    def check_on_error(self, e, msg, need_exit=False):
        if e != vrep.simx_return_ok:
            print('Error: {}'.format(msg))
            if need_exit:
                sys.exit()

    def set_motor_speed(self, left_speed, right_speed):
        e = vrep.simxSetJointTargetVelocity(self.client_id, self.left_motor_handle, left_speed,
                                            vrep.simx_opmode_oneshot_wait)
        self.check_on_error(e, "SetJointTargetVelocity for left motor got error code")
        e = vrep.simxSetJointTargetVelocity(self.client_id, self.right_motor_handle, right_speed,
                                            vrep.simx_opmode_oneshot_wait)
        self.check_on_error(e, "SetJointTargetVelocity for right motor got error code")
        # print("Motor's speed is set to {} {}".format(left_speed, right_speed))

    def fix_distance(self, dist, old_dist):
        # constants
        kp = 2
        kd = 0.5
        ki = 0.05
        iMin, iMax = -0.2, 0.2

        # error calculation
        self.e = self.maintained_dist_right - dist

        # Prop
        up = kp * self.e

        # Diff
        ud = kd * (dist - old_dist)

        # Integral
        self.iSum += self.e
        self.iSum = max(iMin, self.iSum)
        self.iSum = min(iMax, self.iSum)
        ui = ki * self.iSum

        res = up + ud + ui
        self.set_motor_speed(self.left_speed - res, self.right_speed + res)
        self.prev_e = self.e
        return self.left_speed - res, self.right_speed + res

    def calc_dist(self, left_dist, right_dist):
        hyp = math.sqrt(left_dist ** 2 + right_dist ** 2)
        dist = left_dist * right_dist / hyp
        return dist

    def start_simulation(self):
        self.set_motor_speed(self.left_speed, self.right_speed)

        (errorCode, detectionState, detectedPoint, detectedObjectHandle,
         detectedSurfaceNormalVector) = vrep.simxReadProximitySensor(self.client_id, self.laser_left,
                                                                     vrep.simx_opmode_streaming)
        (errorCode, detectionState, detectedPoint, detectedObjectHandle,
         detectedSurfaceNormalVector) = vrep.simxReadProximitySensor(self.client_id, self.laser_right,
                                                                     vrep.simx_opmode_streaming)
        (errorCode, detectionState, detectedPoint, detectedObjectHandle,
         detectedSurfaceNormalVector) = vrep.simxReadProximitySensor(self.client_id, self.laser_front,
                                                                     vrep.simx_opmode_streaming)
        (errorCode, detectionState, detectedPoint, detectedObjectHandle,
         detectedSurfaceNormalVector) = vrep.simxReadProximitySensor(self.client_id, self.laser_middle,
                                                                     vrep.simx_opmode_streaming)
###################################
        res, v0 = vrep.simxGetObjectHandle(self.client_id, 'Vision_sensor', vrep.simx_opmode_oneshot_wait)
        res, resolution, image = vrep.simxGetVisionSensorImage(self.client_id, v0, 0, vrep.simx_opmode_streaming)
##################################
        prev_dist = 0
        err_arr_tmp = np.zeros(100)
        counter = 0

        self.prev_position = \
        vrep.simxGetObjectPosition(self.client_id, self.robot_handle, self.cuboid, vrep.simx_opmode_buffer)[1]
        self.prev_orientation = \
        vrep.simxGetObjectOrientation(self.client_id, self.robot_handle, self.relative_object, vrep.simx_opmode_buffer)[
            1]

        while vrep.simxGetConnectionId(self.client_id) != -1:
            front_detection, left_detection, middle_detection, right_detection, distance_front, distance_left, distance_middle, distance_right = self.get_proximity_data()
            if left_detection and right_detection:
                dist = min(self.calc_dist(distance_left, distance_right), distance_middle)
            elif not left_detection and not right_detection and middle_detection:
                dist = distance_middle
            elif not left_detection:
                dist = 0.9
                if middle_detection:
                    dist = min(dist, distance_middle, distance_right)
            elif left_detection and middle_detection:
                dist = min(distance_left, distance_middle)

            if front_detection:
                dist = min(distance_front, dist)

            err_arr_tmp[counter] = self.maintained_dist_right - dist
            self.fix_distance(dist, prev_dist)

            time.sleep(self.update_time)
            prev_dist = dist

        vrep.simxFinish(self.client_id)
        return

    def get_proximity_data(self):
        (e, left_detection, left, detectedObjectHandle,
         detectedSurfaceNormalVector) = vrep.simxReadProximitySensor(self.client_id, self.laser_left,
                                                                     vrep.simx_opmode_buffer)
        self.check_on_error(e, "Left sensor data reading error")

        (e, right_detection, right, detectedObjectHandle,
         detectedSurfaceNormalVector) = vrep.simxReadProximitySensor(self.client_id, self.laser_right,
                                                                     vrep.simx_opmode_buffer)
        self.check_on_error(e, "Right sensor data reading error")

        (e, front_detection, front, detectedObjectHandle,
         detectedSurfaceNormalVector) = vrep.simxReadProximitySensor(self.client_id, self.laser_front,
                                                                     vrep.simx_opmode_buffer)
        self.check_on_error(e, "Front sensor data reading error")

        (e, middle_detection, middle, detectedObjectHandle,
         detectedSurfaceNormalVector) = vrep.simxReadProximitySensor(self.client_id, self.laser_middle,
                                                                     vrep.simx_opmode_buffer)
        self.check_on_error(e, "Middle sensor data reading error")

        return front_detection, left_detection, middle_detection, right_detection, np.linalg.norm(
            front), np.linalg.norm(left), np.linalg.norm(middle), np.linalg.norm(right)


if __name__ == '__main__':
    robot = Robot()
    robot.start_simulation()
    print('Done')
