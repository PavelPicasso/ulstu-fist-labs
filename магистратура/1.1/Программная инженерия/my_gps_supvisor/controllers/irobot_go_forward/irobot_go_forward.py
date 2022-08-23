"""irobot_go_forward controller."""

# You may need to import some classes of the controller module. Ex:
#  from controller import Robot, Motor, DistanceSensor
from controller import Robot
from controller import GPS
from controller import Keyboard
import struct

cliff_sensors_name = ['cliff_left', 'cliff_front_left', 'cliff_front_right', 'cliff_right']

class MyController(Robot):
    def __init__(self):
        super(MyController, self).__init__()
        self.timeStep = 64  # set the control time step
        self.null_speed = 0
        self.max_speed = 6.28
        self.cliff_sensors = []
        self.robot_coords = []
        self.robot_rotation = []
        self.gps = GPS('gps')
        self.keyboard = Keyboard()
        self.receiver = self.getDevice('receiver')
        
        # get device tags
        self.keyboard.enable(self.timeStep)
        self.gps.enable(self.timeStep)
        self.receiver.enable(self.timeStep)
        
        for i in range(0, len(cliff_sensors_name)):
            self.cliff_sensors.append(self.getDevice(cliff_sensors_name[i]))
            self.cliff_sensors[i].enable(self.timeStep)
        
        self.leftMotor = self.getDevice('left wheel motor')
        self.rightMotor = self.getDevice('right wheel motor')
        self.leftMotor.setPosition(float('inf'))
        self.rightMotor.setPosition(float('inf'))
        self.leftMotor.setVelocity(self.null_speed)
        self.rightMotor.setVelocity(self.null_speed)
        
        self.left_position_sensor = self.getDevice('left wheel sensor')
        self.right_position_sensor = self.getDevice('right wheel sensor')
        self.left_position_sensor.enable(self.timeStep)
        self.right_position_sensor.enable(self.timeStep)
        
        print('Default controller of the iRobot Create robot started...\n');

    def go_forward(self, left_percent, right_percent):
        self.leftMotor.setVelocity(left_percent * self.max_speed)
        self.rightMotor.setVelocity(right_percent * self.max_speed)
    
    def stop(self):
        self.leftMotor.setVelocity(self.null_speed)
        self.rightMotor.setVelocity(self.null_speed)
    
    def get_supervisor_data(self):
        if(self.receiver.getQueueLength() == 0):
            return
        
        while(self.receiver.getQueueLength() > 1):
            self.receiver.nextPacket()
        
        buffer = self.receiver.getData()
        buffer_data = list(struct.unpack('>7f', buffer))
        
        self.robot_coords = buffer_data[:3]
        
        self.robot_rotation = buffer_data[3:]
    
    def run(self):
        print('Press G to read the Supervisors position\n')
        print('Press S to read the Supervisors position\n')
        print('Press D to read the Supervisors position and rotation\n')
        
        while self.step(self.timeStep) != -1:
            
            key_down = self.keyboard.getKey()
            if(key_down == ord('G')):
                gps_values = self.gps.getValues()
                print('Irobot using the GPS: %g %g %g' % (gps_values[0], gps_values[1], gps_values[2]))
            if(key_down == ord('D')):
                self.get_supervisor_data()
                print('coords', self.robot_coords, 'rotation', self.robot_rotation)
            
            
            self.leftMotor.setVelocity(-self.max_speed)
            self.rightMotor.setVelocity(self.max_speed)

# main Python program
controller = MyController()
controller.run()