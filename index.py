i = 27
j= 2
while(j <= (i/j)):
    if not(i%j):
        print("Not a prime")
        break
    j += 1
if (j > i/j):
    print("prime")
print(bool(6%2))