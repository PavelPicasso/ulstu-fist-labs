import random

def _try_composite(a, d, n, s):
    if pow(a, d, n) == 1:
        return False
    for i in range(s):
        if pow(a, 2**i * d, n) == n-1:
            return False
    return True # n  is definitely composite
 
_known_primes = [2, 3]

def isPrime(n, _precision_for_huge_n=16):
    if n in _known_primes:
        return True
    if any((n % p) == 0 for p in _known_primes) or n in (0, 1):
        return False
    d, s = n - 1, 0
    while not d % 2:
        d, s = d >> 1, s + 1
    # Returns exact according to http://primes.utm.edu/prove/prove2_3.html
    if n < 1373653: 
        return not any(_try_composite(a, d, n, s) for a in (2, 3))
    if n < 25326001: 
        return not any(_try_composite(a, d, n, s) for a in (2, 3, 5))
    if n < 118670087467: 
        if n == 3215031751: 
            return False
        return not any(_try_composite(a, d, n, s) for a in (2, 3, 5, 7))
    if n < 2152302898747: 
        return not any(_try_composite(a, d, n, s) for a in (2, 3, 5, 7, 11))
    if n < 3474749660383: 
        return not any(_try_composite(a, d, n, s) for a in (2, 3, 5, 7, 11, 13))
    if n < 341550071728321: 
        return not any(_try_composite(a, d, n, s) for a in (2, 3, 5, 7, 11, 13, 17))
    # otherwise
    return not any(_try_composite(a, d, n, s) 
                   for a in _known_primes[:_precision_for_huge_n])


def totient(number): 
    if(isPrime(number, 3)):
        return number-1
    else:
        return False


# it isnt the best method to compute prime numbers
def prime(n): # check if the number is prime
    if (n <= 1):
        return False
    if (n <= 3):
        return True

    if (n%2 == 0 or n%3 == 0):
        return False

    i = 5
    while(i * i <= n):
        if (n%i == 0 or n%(i+2) == 0):
           return False
        i+=6
    return True


def generate_E(num): 
    def mdc(n1,n2):
        rest = 1
        while(n2 != 0):
            rest = n1%n2
            n1 = n2
            n2 = rest
        return n1

    while True:
        e = random.randrange(2,num) 
        if(mdc(num,e) == 1):
            return e


def generate_prime(): # generate the prime number - p e q
    while True: # 2**2048 is the RSA standart keys
        x=random.randrange(1,100) # define the range of the primes
        if(isPrime(x, 3)==True):
            return x


def mod(a,b): # mod function
    if(a<b):
        return a
    else:
        c=a%b
        return c
    

def mod(a,b): # mod function
    if(a<b):
        return a
    else:
        c=a%b
        return c


def cipher(words,e,n): # get the words and compute the cipher
    tam = len(words)
    i = 0
    lista = []
    while(i < tam):
        letter = words[i]
        k = ord(letter)
        k = k**e
        d = mod(k,n)
        lista.append(d)
        i += 1
    return lista


def descifra(cifra,n,d):
    lista = []
    i = 0
    tamanho = len(cifra)
    # texto=cifra ^ d mod n
    while i < tamanho:
        result = cifra[i]**d
        texto = mod(result,n)
        letra = chr(texto)
        lista.append(letra)
        i += 1
    return lista



def calculate_private_key(toti,e):
    d = 0
    while(mod(d*e,toti)!=1):
        d += 1
    return d




## MAIN
if __name__=='__main__':
    # text = input("Insert message: ")
    f = open('text.txt')
    text = f.read()
    text_input = "Insert message: " + text + ""
    print(text_input + '/')

    p = generate_prime() # generates random P
    q = generate_prime() # generates random Q
    n = p*q # compute N
    y = totient(p) # compute the totient of P
    x = totient(q) # compute the totient of Q
    totient_de_N = x*y # compute the totient of N
    e = generate_E(totient_de_N) # generate E
    public_key = (n, e)

    print('Your public key: ', str(public_key) + '/')
    text_cipher = cipher(text,e,n)
    print('Your encrypted message: ', end='')
    for code in text_cipher:
        print(code, end=' ')
    print('/')
    d = calculate_private_key(totient_de_N,e)
    print('Your private key is:', str(d) + '/')
    original_text = descifra(text_cipher,n,d)
    print('your original message: ', end='')
    for word in original_text:
        print(word, end='')
    print('/')