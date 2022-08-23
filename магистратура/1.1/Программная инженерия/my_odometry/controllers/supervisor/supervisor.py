from controller import Supervisor
from controller import Keyboard
from controller import Emitter
import struct
import sys

TIME_STEP = 64
robot_coords = [[0, 0, 0], [0, 0, 0, 0]]

supervisor = Supervisor()
keyboard = Keyboard()
keyboard.enable(TIME_STEP)
emitter = supervisor.getDevice("emitter")

# do this once only
robot_node = supervisor.getFromDef("E-puck")
if robot_node is None:
    sys.stderr.write("No DEF E-puck node found in the current world file\n")
    sys.exit(1)

trans_field_translation = robot_node.getField('translation')
trans_field_rotation = robot_node.getField('rotation')

print('Press S to read the Supervisors position and rotation\n')
while supervisor.step(TIME_STEP) != -1:
    # this is done repeatedly
    # get handle to robot's translation and rotation field
    translation = trans_field_translation.getSFVec3f()
    rotation = trans_field_rotation.getSFRotation()

    robot_coords[0] = translation
    robot_coords[1] = rotation
    
    if(keyboard.getKey() == ord('S')):
        print('--------------------------------------')
        print('translation:', translation)
        print('rotation:', rotation)
    
    message = struct.pack('>7f', robot_coords[0][0], robot_coords[0][1], robot_coords[0][2], robot_coords[1][0], robot_coords[1][1], robot_coords[1][2], robot_coords[1][3])
    emitter.send(message)