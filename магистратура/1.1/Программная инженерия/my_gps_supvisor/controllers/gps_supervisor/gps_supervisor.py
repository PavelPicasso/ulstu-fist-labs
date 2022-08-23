from controller import Supervisor
from controller import Keyboard
from controller import Emitter
import struct

TIME_STEP = 64
robot_coords = [[0, 0, 0], [0, 0, 0, 0]]

supervisor = Supervisor()
keyboard = Keyboard()
keyboard.enable(TIME_STEP)

# do this once only
robot_node = supervisor.getFromDef("Irobot")
if robot_node is None:
    sys.stderr.write("No DEF Irobot node found in the current world file\n")
    sys.exit(1)


emitter = supervisor.getDevice("emitter")

trans_field = robot_node.getField('translation')
while supervisor.step(TIME_STEP) != -1:
    if(keyboard.getKey() == ord('S')):
        values = trans_field.getSFVec3f()
        print('Using the Supervisor: %g %g %g' % (values[0], values[1], values[2]))
        # print('Using the Supervisor (getPosition): %g %g %g' % (translation[0], translation[1], translation[2]))
    
    translation = robot_node.getPosition()
    rotation = robot_node.getOrientation()
    
    robot_coords[0] = translation
    robot_coords[1] = rotation
    
    message = struct.pack('>7f', robot_coords[0][0], robot_coords[0][1], robot_coords[0][2], robot_coords[1][0], robot_coords[1][1], robot_coords[1][2], robot_coords[1][3])
    emitter.send(message)