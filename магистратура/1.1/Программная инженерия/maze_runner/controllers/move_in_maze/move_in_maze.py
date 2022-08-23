"""Naive maze runner controller."""

from controller import Robot

# Get reference to the robot.
robot = Robot()

# Get simulation step length.
timeStep = int(robot.getBasicTimeStep())

# Constants of the Thymio II motors and distance sensors.
maxMotorVelocity = 9.53

# Get left and right wheel motors.
leftMotor = robot.getDevice("motor.left")
rightMotor = robot.getDevice("motor.right")

# Frontal distance sensors that can be use to detect the walls.
distance_sensor = []
dist_sensor_values = [0, 0, 0, 0, 0, 0, 0]
for ind in range(7):
    distance_sensor.append(robot.getDevice('prox.horizontal.' + str(ind)))
    distance_sensor[ind].enable(timeStep)

# Disable motor PID control mode.
leftMotor.setPosition(float('inf'))
rightMotor.setPosition(float('inf'))

# Set ideal motor velocity.
velocity = 0.5 * maxMotorVelocity

isRotating = False
while robot.step(timeStep) != -1:
    for ind in range(7):
        dist_sensor_values[ind] = distance_sensor[ind].getValue()
    print(dist_sensor_values)
    
    # Read values from four distance sensors.
    if not isRotating and dist_sensor_values[2] > 3500:
        # Black circle detected.
        isRotating = True
    elif isRotating and dist_sensor_values[0] == dist_sensor_values[1] == dist_sensor_values[1] == 0:
        isRotating = False

    leftMotor.setVelocity(velocity)
    if isRotating:
        rightMotor.setVelocity(-velocity)
    else:
        rightMotor.setVelocity(velocity)
