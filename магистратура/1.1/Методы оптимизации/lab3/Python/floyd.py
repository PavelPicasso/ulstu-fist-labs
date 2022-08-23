import time
import timeit

code_to_test = """
inf=1000
m2=[
[0,inf,3,inf,16,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,20,11,inf,inf,inf],
[inf,0,inf,inf,12,5,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf],
[3,inf,0,3,1,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf],
[inf,inf,inf,0,inf,inf,inf,inf,inf,inf,inf,inf,21,inf,inf,inf,inf,inf,inf,inf],
[16,inf,inf,inf,0,7,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf],
[inf,inf,inf,inf,inf,0,inf,inf,inf,inf,8,inf,inf,inf,inf,inf,inf,inf,inf,inf],
[inf,inf,inf,inf,inf,inf,0,inf,inf,inf,inf,inf,inf,1,inf,inf,inf,inf,inf,inf],
[inf,inf,inf,inf,3,inf,inf,0,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf],
[inf,9,inf,inf,inf,inf,inf,inf,0,inf,inf,inf,inf,inf,inf,inf,inf,inf,16,inf],
[inf,inf,inf,inf,inf,inf,inf,inf,inf,0,inf,inf,11,inf,inf,19,inf,inf,inf,inf],
[inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,0,inf,inf,inf,inf,inf,inf,inf,5,inf],
[inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,0,inf,inf,inf,4,inf,inf,inf,inf],
[inf,inf,inf,inf,inf,inf,16,inf,13,inf,inf,inf,0,inf,inf,inf,inf,inf,inf,inf],
[inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,2,0,inf,inf,inf,inf,inf,17],
[inf,inf,inf,inf,inf,inf,3,inf,5,inf,inf,inf,inf,inf,0,inf,inf,inf,inf,inf],
[20,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,0,inf,inf,inf,inf],
[11,inf,inf,inf,inf,inf,inf,11,inf,inf,inf,inf,inf,inf,inf,inf,0,inf,inf,inf],
[inf,2,inf,9,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,0,inf,inf],
[inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,0,inf,inf],
[inf,inf,inf,inf,inf,inf,inf,inf,inf,8,inf,14,inf,inf,inf,inf,inf,inf,inf,0]
]
n2=len(m2)
d=[]
p=[]
for x in m2:
    temp=[]
    temp2=[]
    for y in x:
        temp.append(y)
        temp2.append(-1)
    d.append(temp)
    p.append(temp2)
for x in range(n2):
    for y in range(n2):
        if x==y:
            p[x][y]=0
        elif d[x][y]!=inf:
            p[x][y]=x
        else:
            p[x][y]=-1
for x in range(n2):
    for y in range(n2):
        for z in range(n2):
            if d[y][x]+d[x][z]<d[y][z]:
                d[y][z]=d[y][x]+d[x][z]
                p[y][z]=p[x][z]
"""

def floyd(m2,n2):
	d=[]
	p=[]
	inf=1000
	for x in m2:
		temp=[]
		temp2=[]
		for y in x:
			temp.append(y)
			temp2.append(-1)
		d.append(temp)
		p.append(temp2)
	for x in range(n2):
		for y in range(n2):
			if x==y:
				p[x][y]=0
			elif d[x][y]!=inf:
				p[x][y]=x
			else:
				p[x][y]=-1
	for x in range(n2):
		for y in range(n2):
			for z in range(n2):
				if d[y][x]+d[x][z]<d[y][z]:
					d[y][z]=d[y][x]+d[x][z]
					p[y][z]=p[x][z]
	return d,p

def printAll(p2,n2):
	print("")
	for x in range(n2):
		for y in range(n2):
			if x!=y and p2[x][y]!=-1:
				print("Path dari",x+1,"ke",y+1,"=",x+1,"->",end='')
				printPath(p2,x,y)
				print("",y+1)

def printPath(p3,x2,y2):
	if p3[x2][y2]!=x2:
		printPath(p3,x2,p3[x2][y2])
		print("",p3[x2][y2]+1,"->",end='')

inf=1000
m=[
[0,inf,3,inf,16,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,20,11,inf,inf,inf],
[inf,0,inf,inf,12,5,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf],
[3,inf,0,3,1,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf],
[inf,inf,inf,0,inf,inf,inf,inf,inf,inf,inf,inf,21,inf,inf,inf,inf,inf,inf,inf],
[16,inf,inf,inf,0,7,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf],
[inf,inf,inf,inf,inf,0,inf,inf,inf,inf,8,inf,inf,inf,inf,inf,inf,inf,inf,inf],
[inf,inf,inf,inf,inf,inf,0,inf,inf,inf,inf,inf,inf,1,inf,inf,inf,inf,inf,inf],
[inf,inf,inf,inf,3,inf,inf,0,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf],
[inf,9,inf,inf,inf,inf,inf,inf,0,inf,inf,inf,inf,inf,inf,inf,inf,inf,16,inf],
[inf,inf,inf,inf,inf,inf,inf,inf,inf,0,inf,inf,11,inf,inf,19,inf,inf,inf,inf],
[inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,0,inf,inf,inf,inf,inf,inf,inf,5,inf],
[inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,0,inf,inf,inf,4,inf,inf,inf,inf],
[inf,inf,inf,inf,inf,inf,16,inf,13,inf,inf,inf,0,inf,inf,inf,inf,inf,inf,inf],
[inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,2,0,inf,inf,inf,inf,inf,17],
[inf,inf,inf,inf,inf,inf,3,inf,5,inf,inf,inf,inf,inf,0,inf,inf,inf,inf,inf],
[20,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,0,inf,inf,inf,inf],
[11,inf,inf,inf,inf,inf,inf,11,inf,inf,inf,inf,inf,inf,inf,inf,0,inf,inf,inf],
[inf,2,inf,9,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,0,inf,inf],
[inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,inf,0,inf,inf],
[inf,inf,inf,inf,inf,inf,inf,inf,inf,8,inf,14,inf,inf,inf,inf,inf,inf,inf,0]
]
n=len(m)
#print("\nTabel jarak mula-mula:")
#for x in m:
#	print(x)


#start_timer = time.time()
#for i in range(0, 100000):
#        result,path=floyd(m,n)
#print(time.time() - start_timer)


#print("\nTabel jarak terpendek:")
#for x in result:
#	print(x)
#printAll(path,n)

elapsed_time = timeit.timeit(code_to_test, number=100000)/100000
print(elapsed_time)
