	.file	"Source.cpp"
	.text
	.section .rdata,"dr"
_ZStL19piecewise_construct:
	.space 1
.lcomm _ZStL8__ioinit,1,1
	.def	__main;	.scl	2;	.type	32;	.endef
	.align 8
.LC0:
	.ascii "HARDWARE\\DESCRIPTION\\System\\BIOS\0"
.LC1:
	.ascii "BIOSVersion\0"
.LC2:
	.ascii "BIOSVersion \0"
.LC3:
	.ascii "\12\0"
	.align 8
.LC4:
	.ascii "SYSTEM\\CurrentControlSet\\Control\\ComputerName\\ComputerName\0"
.LC5:
	.ascii "ComputerName\0"
.LC6:
	.ascii "ComputerName \0"
	.align 8
.LC7:
	.ascii "SYSTEM\\CurrentControlSet\\Control\\SystemInformation\0"
.LC8:
	.ascii "SystemManufacturer\0"
.LC9:
	.ascii "SystemManufacturer \0"
.LC10:
	.ascii "UserName \0"
	.text
	.globl	main
	.def	main;	.scl	2;	.type	32;	.endef
	.seh_proc	main
main:
.LFB5954:
	pushq	%rbp
	.seh_pushreg	%rbp
	subq	$1120, %rsp
	.seh_stackalloc	1120
	leaq	128(%rsp), %rbp
	.seh_setframe	%rbp, 128
	.seh_endprologue
	call	__main
	movl	$256, 204(%rbp)
	movl	$257, -68(%rbp)
	leaq	984(%rbp), %rax
	movq	%rax, 32(%rsp)
	movl	$1, %r9d
	movl	$0, %r8d
	leaq	.LC0(%rip), %rdx
	movq	$-2147483646, %rcx
	movq	__imp_RegOpenKeyExA(%rip), %rax
	call	*%rax
	movq	984(%rbp), %rax
	leaq	204(%rbp), %rdx
	movq	%rdx, 40(%rsp)
	leaq	720(%rbp), %rdx
	movq	%rdx, 32(%rsp)
	movl	$0, %r9d
	movl	$0, %r8d
	leaq	.LC1(%rip), %rdx
	movq	%rax, %rcx
	movq	__imp_RegQueryValueExA(%rip), %rax
	call	*%rax
	leaq	.LC2(%rip), %rdx
	movq	.refptr._ZSt4cout(%rip), %rcx
	call	_ZStlsISt11char_traitsIcEERSt13basic_ostreamIcT_ES5_PKc
	movq	%rax, %rcx
	leaq	720(%rbp), %rax
	movq	%rax, %rdx
	call	_ZStlsISt11char_traitsIcEERSt13basic_ostreamIcT_ES5_PKc
	leaq	.LC3(%rip), %rdx
	movq	%rax, %rcx
	call	_ZStlsISt11char_traitsIcEERSt13basic_ostreamIcT_ES5_PKc
	movl	$256, 204(%rbp)
	leaq	976(%rbp), %rax
	movq	%rax, 32(%rsp)
	movl	$1, %r9d
	movl	$0, %r8d
	leaq	.LC4(%rip), %rdx
	movq	$-2147483646, %rcx
	movq	__imp_RegOpenKeyExA(%rip), %rax
	call	*%rax
	movq	976(%rbp), %rax
	leaq	204(%rbp), %rdx
	movq	%rdx, 40(%rsp)
	leaq	464(%rbp), %rdx
	movq	%rdx, 32(%rsp)
	movl	$0, %r9d
	movl	$0, %r8d
	leaq	.LC5(%rip), %rdx
	movq	%rax, %rcx
	movq	__imp_RegQueryValueExA(%rip), %rax
	call	*%rax
	leaq	.LC6(%rip), %rdx
	movq	.refptr._ZSt4cout(%rip), %rcx
	call	_ZStlsISt11char_traitsIcEERSt13basic_ostreamIcT_ES5_PKc
	movq	%rax, %rcx
	leaq	464(%rbp), %rax
	movq	%rax, %rdx
	call	_ZStlsISt11char_traitsIcEERSt13basic_ostreamIcT_ES5_PKc
	leaq	.LC3(%rip), %rdx
	movq	%rax, %rcx
	call	_ZStlsISt11char_traitsIcEERSt13basic_ostreamIcT_ES5_PKc
	movl	$256, 204(%rbp)
	leaq	976(%rbp), %rax
	movq	%rax, 32(%rsp)
	movl	$1, %r9d
	movl	$0, %r8d
	leaq	.LC7(%rip), %rdx
	movq	$-2147483646, %rcx
	movq	__imp_RegOpenKeyExA(%rip), %rax
	call	*%rax
	movq	976(%rbp), %rax
	leaq	204(%rbp), %rdx
	movq	%rdx, 40(%rsp)
	leaq	208(%rbp), %rdx
	movq	%rdx, 32(%rsp)
	movl	$0, %r9d
	movl	$0, %r8d
	leaq	.LC8(%rip), %rdx
	movq	%rax, %rcx
	movq	__imp_RegQueryValueExA(%rip), %rax
	call	*%rax
	leaq	.LC9(%rip), %rdx
	movq	.refptr._ZSt4cout(%rip), %rcx
	call	_ZStlsISt11char_traitsIcEERSt13basic_ostreamIcT_ES5_PKc
	movq	%rax, %rcx
	leaq	208(%rbp), %rax
	movq	%rax, %rdx
	call	_ZStlsISt11char_traitsIcEERSt13basic_ostreamIcT_ES5_PKc
	leaq	.LC3(%rip), %rdx
	movq	%rax, %rcx
	call	_ZStlsISt11char_traitsIcEERSt13basic_ostreamIcT_ES5_PKc
	leaq	-68(%rbp), %rdx
	leaq	-64(%rbp), %rax
	movq	%rax, %rcx
	movq	__imp_GetUserNameA(%rip), %rax
	call	*%rax
	leaq	.LC10(%rip), %rdx
	movq	.refptr._ZSt4cout(%rip), %rcx
	call	_ZStlsISt11char_traitsIcEERSt13basic_ostreamIcT_ES5_PKc
	movq	%rax, %rcx
	leaq	-64(%rbp), %rax
	movq	%rax, %rdx
	call	_ZStlsISt11char_traitsIcEERSt13basic_ostreamIcT_ES5_PKc
	leaq	.LC3(%rip), %rdx
	movq	%rax, %rcx
	call	_ZStlsISt11char_traitsIcEERSt13basic_ostreamIcT_ES5_PKc
	call	getchar
	movl	$0, %eax
	addq	$1120, %rsp
	popq	%rbp
	ret
	.seh_endproc
	.def	__tcf_0;	.scl	3;	.type	32;	.endef
	.seh_proc	__tcf_0
__tcf_0:
.LFB6439:
	pushq	%rbp
	.seh_pushreg	%rbp
	movq	%rsp, %rbp
	.seh_setframe	%rbp, 0
	subq	$32, %rsp
	.seh_stackalloc	32
	.seh_endprologue
	leaq	_ZStL8__ioinit(%rip), %rcx
	call	_ZNSt8ios_base4InitD1Ev
	nop
	addq	$32, %rsp
	popq	%rbp
	ret
	.seh_endproc
	.def	_Z41__static_initialization_and_destruction_0ii;	.scl	3;	.type	32;	.endef
	.seh_proc	_Z41__static_initialization_and_destruction_0ii
_Z41__static_initialization_and_destruction_0ii:
.LFB6438:
	pushq	%rbp
	.seh_pushreg	%rbp
	movq	%rsp, %rbp
	.seh_setframe	%rbp, 0
	subq	$32, %rsp
	.seh_stackalloc	32
	.seh_endprologue
	movl	%ecx, 16(%rbp)
	movl	%edx, 24(%rbp)
	cmpl	$1, 16(%rbp)
	jne	.L6
	cmpl	$65535, 24(%rbp)
	jne	.L6
	leaq	_ZStL8__ioinit(%rip), %rcx
	call	_ZNSt8ios_base4InitC1Ev
	leaq	__tcf_0(%rip), %rcx
	call	atexit
.L6:
	nop
	addq	$32, %rsp
	popq	%rbp
	ret
	.seh_endproc
	.def	_GLOBAL__sub_I_main;	.scl	3;	.type	32;	.endef
	.seh_proc	_GLOBAL__sub_I_main
_GLOBAL__sub_I_main:
.LFB6440:
	pushq	%rbp
	.seh_pushreg	%rbp
	movq	%rsp, %rbp
	.seh_setframe	%rbp, 0
	subq	$32, %rsp
	.seh_stackalloc	32
	.seh_endprologue
	movl	$65535, %edx
	movl	$1, %ecx
	call	_Z41__static_initialization_and_destruction_0ii
	nop
	addq	$32, %rsp
	popq	%rbp
	ret
	.seh_endproc
	.section	.ctors,"w"
	.align 8
	.quad	_GLOBAL__sub_I_main
	.ident	"GCC: (x86_64-posix-seh-rev0, Built by MinGW-W64 project) 7.3.0"
	.def	_ZStlsISt11char_traitsIcEERSt13basic_ostreamIcT_ES5_PKc;	.scl	2;	.type	32;	.endef
	.def	getchar;	.scl	2;	.type	32;	.endef
	.def	_ZNSt8ios_base4InitD1Ev;	.scl	2;	.type	32;	.endef
	.def	_ZNSt8ios_base4InitC1Ev;	.scl	2;	.type	32;	.endef
	.def	atexit;	.scl	2;	.type	32;	.endef
	.section	.rdata$.refptr._ZSt4cout, "dr"
	.globl	.refptr._ZSt4cout
	.linkonce	discard
.refptr._ZSt4cout:
	.quad	_ZSt4cout
