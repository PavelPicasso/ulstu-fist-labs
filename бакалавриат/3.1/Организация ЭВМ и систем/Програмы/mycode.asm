.data   

mes db 0ah,0dh,'arr - ','$'
mas db 77 dup (?)
i db 0

.code
main:   

mov ax,@data
mov ds,ax
xor ax,ax
mov cx,77
mov si,0 
push cx

go: 
    mov bh,i 
    mov mas[si],bh
    inc i
    inc si 
loop go 

pop cx
mov si,2
mov ah,09h
lea dx,mes
int 21h         

add cx,1
cmp si, cx
jc show      
show:             
    mov dl,mas[si]
    
    cmp dl,0
    je next
    mov ah,02h
    
    call print
    mov dl, ' '
    int 21h 
    
    mov ax,si
    mul ax
    mov di,ax
    
    cmp di, cx
    jc A
    A:
        mov mas[di],0
        add di,si
        cmp di, cx
        jc A 
        
    next:
    inc si
    
    cmp si, cx
    jc show      
    
jmp exit   
         

print:
    push cx
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
    pop cx
ret             
    

  
         
exit:
    mov ax,4c00h
    int 21h
end main 