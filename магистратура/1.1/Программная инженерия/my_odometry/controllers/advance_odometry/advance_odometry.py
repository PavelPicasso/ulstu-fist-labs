from controller import Robot
import math
import struct

class MyController(Robot):
    def __init__(self):
        super(MyController, self).__init__()
        self.timeStep = 64  # set the control time step
        self.null_speed = 0
        self.max_speed = 6.28
        self.speed = [0, 0]
        
        self.wheel_radius = 0.02
        self.distance_between_weels = 0.05685
        self.diff = 0
        self.v = 0
        self.w = 0
        self.vx = 0
        self.vy = 0   
        
        self.distance_sensor = []
        self.dist_sensor_values = [0, 0, 0, 0, 0, 0, 0, 0]
        self.robot_pos = [0, 0, 0]
        self.last_ps_values = [0, 0]
        self.ps_values = [0, 0]
        self.dist_values = [0, 0]
        self.last_goal_dist = math.inf
        
        self.robot_coords = [0, 0, 0]
        self.robot_rotation = [0, 0, 0, 0]
        
        self.left_obstacle = False        
        self.right_obstacle = False
        self.is_forward = False
        self.finish = False
        
        self.weel_cirum = 2 * math.pi * self.wheel_radius
        self.encoder_unit = self.weel_cirum / 6.28
        
        # get device tags             
        self.left_motor = self.getDevice('left wheel motor')
        self.right_motor = self.getDevice('right wheel motor')
        
        self.left_motor.setPosition(float('inf'))
        self.left_motor.setVelocity(self.null_speed)

        self.right_motor.setPosition(float('inf'))
        self.right_motor.setVelocity(self.null_speed)
        
        self.left_ps = self.getDevice('left wheel sensor')
        self.left_ps.enable(self.timeStep)
                
        self.right_ps = self.getDevice('right wheel sensor')
        self.right_ps.enable(self.timeStep)
        
        for ind in range(8):
            self.distance_sensor.append(self.getDevice('ps' + str(ind)))
            self.distance_sensor[ind].enable(self.timeStep)
        
        self.gyro = self.getDevice('gyro')
        self.gyro.enable(self.timeStep)
        
        self.receiver = self.getDevice('receiver')
        self.receiver.enable(self.timeStep)
        
        print('Default controller of the E-puck robot started...\n');
    
    def set_motor_speed(self, left, right):
        """ Set up the motor speeds
        Parameters
        ----------
        left : float
            Current values of the speed, acceleration, and torque/force of the left motor
        right : float
            Current values of the speed, acceleration, and torque/force of the right motor
        """
        
        self.left_motor.setVelocity(left)
        self.right_motor.setVelocity(right)
    
    def get_supervisor_data(self):
        """ Get the current robot coordinates from the supervisor
        """
        
        if(self.receiver.getQueueLength() == 0):
            print('not data')
            return
        
        while(self.receiver.getQueueLength() > 1):
            self.receiver.nextPacket()
        
        buffer = self.receiver.getData()
        buffer_data = list(struct.unpack('>7f', buffer))
        
        self.robot_coords = buffer_data[:3]
        
        self.robot_rotation = buffer_data[3:]
        
        # print('-----------------------------------')
        # print('robot_coords', self.robot_coords)
        # print('robot_rotation', self.robot_rotation)
    
    def obstacle_avoidance(self):
        """ Сhecking the proximity sensor for a remote obstacle
        """
        
        self.left_obstacle = self.dist_sensor_values[5] > 80.0 or self.dist_sensor_values[6] > 80.0 or self.dist_sensor_values[7] > 80.0
        self.right_obstacle = self.dist_sensor_values[0] > 80.0 or self.dist_sensor_values[1] > 80.0 or self.dist_sensor_values[2] > 80.0
        
        print('left', self.left_obstacle, 'right', self.right_obstacle)
    
    def go_to_position(self, x_purpose, y_purpose):
        """ Move a Robot to a Certain Point
        Parameters
        ----------
        x_purpose : float
            target value of the x coordinate to the target
        y_purpose : float
            target value of the y coordinate to the target
        """
        
        inc_x = self.robot_coords[0] - x_purpose
        inc_y = self.robot_coords[2] - y_purpose
        
        print(self.robot_coords)
        
        # compare this current orientation with the actual
        angle_to_goal = math.atan2(inc_y, inc_x)
        d_goal = math.sqrt(math.pow(x_purpose - self.robot_coords[0], 2) + math.pow(y_purpose - self.robot_coords[2], 2))
        
        # print(angle_to_goal, math.fabs(angle_to_goal - self.robot_rotation[3]) , d_goal, self.last_goal_dist)
        
        if self.last_goal_dist < d_goal:
            self.is_forward = False
        
        if not self.is_forward and math.fabs(angle_to_goal - self.robot_rotation[3]) > 0.1:    
            self.set_motor_speed(-0.3 * self.max_speed, 0.3 * self.max_speed)
        else:
            self.is_forward = True
            if d_goal > 0.02:
                self.set_motor_speed(0.3 * self.max_speed, 0.3 * self.max_speed)
            else:
                self.set_motor_speed(self.null_speed, self.null_speed)
                if not self.finish:
                    print('Goal is reached.');
                    self.finish = True
        
        self.last_goal_dist = d_goal
    
    def run(self):
        """ Running a simulator whose process runs in an infinite loop until it is terminated by webbots or by stopping the simulation
        """
        
        while self.step(self.timeStep) != 1:
            for ind in range(8):
                self.dist_sensor_values[ind] = self.distance_sensor[ind].getValue()
            
            self.ps_values[0] = self.left_ps.getValue()
            self.ps_values[1] = self.right_ps.getValue()
    
            # print('-----------------------------------')
            # print('position sensor values: {} {}'.format(self.ps_values[0], self.ps_values[1]))
    
            # now with this we can compute the distance traveled
            for ind in range(2):
                self.diff = self.ps_values[ind] - self.last_ps_values[ind]
                if self.diff < 0.001:
                    self.diff = 0
                    self.ps_values[ind] = self.last_ps_values[ind]
                self.dist_values[ind] = self.ps_values[ind] * self.encoder_unit
    
            # print('distance values: {} {}'.format(self.dist_values[0], self.dist_values[1]))
            
            # compute linear and angular velocity for robot
            self.v = (self.dist_values[0] + self.dist_values[1]) / 2.0
            self.w = (self.dist_values[0] - self.dist_values[1]) / self.distance_between_weels
            
            self.robot_pos[2] += self.w
            
            self.vx = self.v * math.cos(self.robot_pos[2])
            self.vy = self.v * math.sin(self.robot_pos[2])
            
            self.robot_pos[0] += self.vx
            self.robot_pos[1] += self.vy
            
            # print('robot_pose: {}'.format(self.robot_pos))
            
            self.get_supervisor_data();
            self.go_to_position(0.5, 0.5)
            
            for ind in range(2):
                self.last_ps_values[ind] = self.ps_values[ind]


controller = MyController()
controller.run()