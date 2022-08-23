.MODEL SMALL
.STACK 200h

.386

; Декларации данных
.DATA 
InputText db 13, 10, "Input text: $"
text db 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', ' ', '!', '"', '#', '$', '%', '&', '`', '(', ')', '*', '+', ',', '-', '.', '/', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ':', ';', '<', '=', '>', '?', 'А', 'Б', 'В', 'Г', 'Д', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'
count db 94 dup (0)
len dw ?
i dw ?
j dw ?

;========================= Программа =========================
.CODE

BEGIN	LABEL	NEAR
    ; инициализация сегментного регистра
	mov	AX,	@DATA
	mov	DS,	AX
                    
    mov ah,9
    mov dx,offset InputText
    int 21h
    
    mov si, 1
    mov len, 0
go: 
    mov ah, 01h
	int 21h
	cmp al, 0dh
	je getout

	cmp al, 'а'
	jae t1
	jmp en
t1:
    cmp al, 'п'
    jbe t2
    cmp al, 'р'
    jae eng
t2:
    sub al, 'а'
    add al, 58
    call something
    add count[si], 1
    jmp go
    
eng:
    cmp al, 'я'
    jbe t3
    jmp en
    
t3:
    sub al, 'р'
    add al, 74
    call something
    add count[si], 1
    jmp go     	

en:	
	cmp al,'a'
    jb n1
    cmp al,'z'
    jbe n2
    
n2:
    sub al, 20h
    jmp n1   
n1: 
    cmp al, 3fh
    ja q1    
    sub al, 20h
    add al, 26
    call something
    add count[si], 1
    jmp go

q1:
    call something
    sub si, 41h
    
    add count[si], 1
    jmp go
    
getout:     
    
    mov bl, 0
    mov si, 0
    mov j, 94
    call letterLen  
    mov dx, len
    mov i, dx
    mov dx, 0
    mov j, 94
  

l1:     
    cmp i, 0
    je next
    mov si, 0
    
k2: 
    cmp j, 0
    je show   
    mov cl, count[si]
    
    cmp cl,0
    je k1 
        cmp cl, bl
        jbe k1
        
        mov bl, cl ; max
        mov dx, si ;imax
        add len, 1
        jmp k1        
    
k1: 
    inc si
    sub j, 1
    jmp k2

show:
    cmp len,0
    je k3
    mov si, dx
    mov ah, 02h
    mov dl, text[si]
    int 21h
    mov dl, '-'
    int 21h

    mov ah,02h
    mov dl, count[si]
    call print
    mov dl, ' '
    int 21h
    mov count[si], 0
    mov bl, 0
    sub len, 1
    jmp k3         
    
k3:
    sub i, 1 
    mov j, 94
    jmp l1


something:
    mov si, ax
    mov dx, si
    mov dh, 0
    mov si, dx

ret

letterLen:

p2: 
    cmp j, 0
    je way   
    mov cl, count[si]
    
    cmp cl,0
    je w1 
    add len, 1
    jmp w1        
    
w1: 
    inc si
    sub j, 1
    jmp p2

way:
ret


print:
    mov ax, dx
    xor     cx, cx
    mov     bx, 10
oi2:
    xor     dx,dx
    div     bx
    push    dx
    inc     cx
    test    ax, ax
    jnz     oi2
    mov     ah, 02h
oi3:
    pop     dx
    add     dl, '0'
    int     21h
    loop    oi3
ret 
    
next:
    mov ax, 4c00h
    int 21h    
END	BEGIN
