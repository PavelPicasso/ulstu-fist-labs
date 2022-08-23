	.file	"Source.cpp"
	.text
	.globl	_Z9ShellSortiPi
	.def	_Z9ShellSortiPi;	.scl	2;	.type	32;	.endef
	.seh_proc	_Z9ShellSortiPi
_Z9ShellSortiPi:
.LFB28:
	pushq	%rbp
	.seh_pushreg	%rbp
	movq	%rsp, %rbp
	.seh_setframe	%rbp, 0
	subq	$16, %rsp
	.seh_stackalloc	16
	.seh_endprologue
	movl	%ecx, 16(%rbp)
	movq	%rdx, 24(%rbp)
	movl	16(%rbp), %eax
	movl	%eax, %edx
	shrl	$31, %edx
	addl	%edx, %eax
	sarl	%eax
	movl	%eax, -12(%rbp)
.L9:
	cmpl	$0, -12(%rbp)
	jle	.L10
	movl	-12(%rbp), %eax
	movl	%eax, -4(%rbp)
.L8:
	movl	-4(%rbp), %eax
	cmpl	16(%rbp), %eax
	jge	.L3
	movl	-4(%rbp), %eax
	cltq
	leaq	0(,%rax,4), %rdx
	movq	24(%rbp), %rax
	addq	%rdx, %rax
	movl	(%rax), %eax
	movl	%eax, -16(%rbp)
	movl	-4(%rbp), %eax
	movl	%eax, -8(%rbp)
.L7:
	movl	-8(%rbp), %eax
	cmpl	-12(%rbp), %eax
	jl	.L4
	movl	-8(%rbp), %eax
	subl	-12(%rbp), %eax
	cltq
	leaq	0(,%rax,4), %rdx
	movq	24(%rbp), %rax
	addq	%rdx, %rax
	movl	(%rax), %eax
	cmpl	%eax, -16(%rbp)
	jge	.L11
	movl	-8(%rbp), %eax
	subl	-12(%rbp), %eax
	cltq
	leaq	0(,%rax,4), %rdx
	movq	24(%rbp), %rax
	addq	%rdx, %rax
	movl	-8(%rbp), %edx
	movslq	%edx, %rdx
	leaq	0(,%rdx,4), %rcx
	movq	24(%rbp), %rdx
	addq	%rcx, %rdx
	movl	(%rax), %eax
	movl	%eax, (%rdx)
	movl	-12(%rbp), %eax
	subl	%eax, -8(%rbp)
	jmp	.L7
.L11:
	nop
.L4:
	movl	-8(%rbp), %eax
	cltq
	leaq	0(,%rax,4), %rdx
	movq	24(%rbp), %rax
	addq	%rax, %rdx
	movl	-16(%rbp), %eax
	movl	%eax, (%rdx)
	addl	$1, -4(%rbp)
	jmp	.L8
.L3:
	movl	-12(%rbp), %eax
	movl	%eax, %edx
	shrl	$31, %edx
	addl	%edx, %eax
	sarl	%eax
	movl	%eax, -12(%rbp)
	jmp	.L9
.L10:
	nop
	addq	$16, %rsp
	popq	%rbp
	ret
	.seh_endproc
	.def	__main;	.scl	2;	.type	32;	.endef
	.section .rdata,"dr"
.LC0:
	.ascii "Sorted array:\0"
.LC1:
	.ascii "%d \0"
	.text
	.globl	main
	.def	main;	.scl	2;	.type	32;	.endef
	.seh_proc	main
main:
.LFB29:
	pushq	%rbp
	.seh_pushreg	%rbp
	movq	%rsp, %rbp
	.seh_setframe	%rbp, 0
	subq	$64, %rsp
	.seh_stackalloc	64
	.seh_endprologue
	call	__main
	movl	$5, -8(%rbp)
	movl	$3, -32(%rbp)
	movl	$1, -28(%rbp)
	movl	$5, -24(%rbp)
	movl	$2, -20(%rbp)
	movl	$4, -16(%rbp)
	leaq	-32(%rbp), %rdx
	movl	-8(%rbp), %eax
	movl	%eax, %ecx
	call	_Z9ShellSortiPi
	leaq	.LC0(%rip), %rcx
	call	puts
	movl	$0, -4(%rbp)
.L14:
	movl	-4(%rbp), %eax
	cmpl	-8(%rbp), %eax
	jge	.L13
	movl	-4(%rbp), %eax
	cltq
	movl	-32(%rbp,%rax,4), %eax
	movl	%eax, %edx
	leaq	.LC1(%rip), %rcx
	call	printf
	addl	$1, -4(%rbp)
	jmp	.L14
.L13:
	movl	$10, %ecx
	call	putchar
	movl	$0, %eax
	addq	$64, %rsp
	popq	%rbp
	ret
	.seh_endproc
	.ident	"GCC: (x86_64-posix-seh-rev0, Built by MinGW-W64 project) 7.3.0"
	.def	puts;	.scl	2;	.type	32;	.endef
	.def	printf;	.scl	2;	.type	32;	.endef
	.def	putchar;	.scl	2;	.type	32;	.endef
