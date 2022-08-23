import math
import matplotlib.pyplot as plt
import numpy as np
import pandas as pd
import pylab
from mpl_toolkits.mplot3d import Axes3D

pd.set_option("display.precision", 2)

y = []
x_1 = np.linspace(start=0, stop=1.57, num=444)
x_2 = np.linspace(start=0, stop=1.57, num=444)
for i, j in zip(x_1, x_2):
    y.append(0.01 * math.tan(i) * j**2)

df = pd.DataFrame({'x_1': x_1, 'x_2': x_2, 'y': y})
df.to_csv('test.csv', index=False)

print(df.head())
print('\nmax x_1 =', df['x_1'].max())
print('min x_1 =', df['x_1'].min())
print('mean x_1 =', df['x_2'].mean())
print('\nmax x_2 =', df['x_2'].max())
print('min x_2 =', df['x_2'].min())
print('mean x_2 =', df['x_2'].mean())
print('\nmax y =', df['y'].max())
print('min y =', df['y'].min())
print('mean y =', df['y'].mean())


df[(df['x_1'] < df['x_1'].mean()) | (df['x_2'] < df['x_2'].mean())].to_csv('test_new.csv')
print(df[(df['x_1'] < df['x_1'].mean()) | (df['x_2'] < df['x_2'].mean())])


y_1 = 0.01 * np.tan(x_1) * 1**2 # пересчитываем с константой x2
plt.plot(x_1, y_1)
plt.show()
y_2 = 0.01 * np.tan(1) * (x_2 * 17)**2 #пересчитываем с константой x1, домножили на коэффицент 17 для лучшего отображения
plt.plot(x_2, y_2)
plt.show()


plt.plot(x_1, y_1, x_2, y_2)
plt.xlabel('x')
plt.ylabel('y')
plt.savefig('plot.png')
plt.close()

fig = plt.figure()
ax = Axes3D(fig)
X = x_1
Y = x_2
X, Y = np.meshgrid(X, Y)
Z = 0.01 * np.tan(X) * Y**2
surf = ax.plot_surface(X, Y, Z)
plt.savefig('Axes3D.png')
plt.show()


# df = pd.DataFrame(np.random.randn(444, 2), columns=['x1', 'x2'])
# df.to_csv('example.csv', index=False, sep='\t')
# x1 = df['x1']
# x2 = df['x2']
# y = []
# for i, j in zip(x1, x2):
#     y.append(0.01 * math.tan(i) * j**2)
# df['y'] = pd.Series(y, index=df.index)
# print(df.head())