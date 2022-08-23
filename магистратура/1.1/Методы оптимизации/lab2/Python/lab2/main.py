# Подключение библиотек
import time
import math
import pylab
import matplotlib.pyplot as plt
from matplotlib import mlab

# Определение переменных
A = 1.5
B = 1
C = 3
D = 1.5
x_min = 2.2
x_max = 4.4

# Определение функции
function_f = lambda x: D * math.sin(A * x ** B + C)


# Метод золотого сечения
def Golden_Section_Method(x_min, x_max, eps):
    iteration = 1.0
    #print((" {0:.8s} || {1:.5s}  || {2:.8s} || {3:.5s}  || {4:.8s}").format("Итерация", "x_min", "f(x_min)", "x_max",
    #                                                                        "f(x_max)"))
    coefficient = (math.sqrt(5) - 1) / 2
    d = x_min + (x_max - x_min) * coefficient
    c = x_max - (x_max - x_min) * coefficient
    sc = function_f(c)
    sd = function_f(d)
    while (x_max - x_min) > eps:
        if (sd < sc):
            x_max = d
            d = c
            c = x_max - (x_max - x_min) * coefficient
            sd = sc
            sc = function_f(c)
        else:
            x_min = c
            c = d
            d = x_min + (x_max - x_min) * coefficient
            sc = sd
            sd = function_f(d)
        iteration += 1
        #print(("     {0:.0f}    || {1:.4f} || {2:.4f}   || {3:.4f} || {4:.4f}").format(iteration - 1, x_min,
        #                                                                               function_f(x_min), x_max,
        #                                                                               function_f(x_max)))

start_time = time.time()
for j in range(1000):
    Golden_Section_Method(x_min, x_max, 0.0000000001)
print(time.time() - start_time)
# Шаг между точками
dx = 0.1

# Создадим список координат по оси X на отрезке [-x_min; x_max], включая концы
xlist = mlab.frange(x_min, x_max, dx)

# Вычислим значение функции в заданных точках
ylist = [function_f(x) for x in xlist]

# Нарисуем одномерный график
pylab.plot(xlist, ylist)
plt.grid(True)

# Покажем окно с нарисованным графиком
pylab.show()