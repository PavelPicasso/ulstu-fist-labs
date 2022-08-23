
_interrupt:

;USB.c,5 :: 		void interrupt()
;USB.c,7 :: 		USB_Interrupt_Proc();
	CALL        _USB_Interrupt_Proc+0, 0
;USB.c,8 :: 		TMR0L = 100;       //Reload Value                   P
	MOVLW       100
	MOVWF       TMR0L+0 
;USB.c,9 :: 		INTCON.TMR0IF = 0;    //Re-Enable Timer-0 Interrupt
	BCF         INTCON+0, 2 
;USB.c,10 :: 		}
L_end_interrupt:
L__interrupt25:
	RETFIE      1
; end of _interrupt

_clrUSB:

;USB.c,12 :: 		void clrUSB()
;USB.c,14 :: 		memset(Write_Buffer, 0, sizeof Write_Buffer);
	MOVLW       _Write_Buffer+0
	MOVWF       FARG_memset_p1+0 
	MOVLW       hi_addr(_Write_Buffer+0)
	MOVWF       FARG_memset_p1+1 
	CLRF        FARG_memset_character+0 
	MOVLW       64
	MOVWF       FARG_memset_n+0 
	MOVLW       0
	MOVWF       FARG_memset_n+1 
	CALL        _memset+0, 0
;USB.c,15 :: 		while(!HID_Write(&Write_Buffer, sizeof Write_Buffer));
L_clrUSB0:
	MOVLW       _Write_Buffer+0
	MOVWF       FARG_HID_Write_writebuff+0 
	MOVLW       hi_addr(_Write_Buffer+0)
	MOVWF       FARG_HID_Write_writebuff+1 
	MOVLW       64
	MOVWF       FARG_HID_Write_len+0 
	CALL        _HID_Write+0, 0
	MOVF        R0, 1 
	BTFSS       STATUS+0, 2 
	GOTO        L_clrUSB1
	GOTO        L_clrUSB0
L_clrUSB1:
;USB.c,16 :: 		}
L_end_clrUSB:
	RETURN      0
; end of _clrUSB

_UART1_Write_Text_Newline:

;USB.c,39 :: 		void UART1_Write_Text_Newline(unsigned char msg[])
;USB.c,41 :: 		UART1_Write_Text(msg);
	MOVF        FARG_UART1_Write_Text_Newline_msg+0, 0 
	MOVWF       FARG_UART1_Write_Text_uart_text+0 
	MOVF        FARG_UART1_Write_Text_Newline_msg+1, 0 
	MOVWF       FARG_UART1_Write_Text_uart_text+1 
	CALL        _UART1_Write_Text+0, 0
;USB.c,42 :: 		UART1_Write(10);
	MOVLW       10
	MOVWF       FARG_UART1_Write_data_+0 
	CALL        _UART1_Write+0, 0
;USB.c,43 :: 		UART1_Write(13);
	MOVLW       13
	MOVWF       FARG_UART1_Write_data_+0 
	CALL        _UART1_Write+0, 0
;USB.c,44 :: 		}
L_end_UART1_Write_Text_Newline:
	RETURN      0
; end of _UART1_Write_Text_Newline

_clear_buffer:

;USB.c,46 :: 		void clear_buffer(unsigned char buffer[])
;USB.c,48 :: 		unsigned int i = 0;
	CLRF        clear_buffer_i_L0+0 
	CLRF        clear_buffer_i_L0+1 
;USB.c,49 :: 		while(buffer[i] != '\0')
L_clear_buffer2:
	MOVF        clear_buffer_i_L0+0, 0 
	ADDWF       FARG_clear_buffer_buffer+0, 0 
	MOVWF       FSR0L 
	MOVF        clear_buffer_i_L0+1, 0 
	ADDWFC      FARG_clear_buffer_buffer+1, 0 
	MOVWF       FSR0H 
	MOVF        POSTINC0+0, 0 
	XORLW       0
	BTFSC       STATUS+0, 2 
	GOTO        L_clear_buffer3
;USB.c,51 :: 		buffer[i] = '\0';
	MOVF        clear_buffer_i_L0+0, 0 
	ADDWF       FARG_clear_buffer_buffer+0, 0 
	MOVWF       FSR1L 
	MOVF        clear_buffer_i_L0+1, 0 
	ADDWFC      FARG_clear_buffer_buffer+1, 0 
	MOVWF       FSR1H 
	CLRF        POSTINC1+0 
;USB.c,52 :: 		i++;
	INFSNZ      clear_buffer_i_L0+0, 1 
	INCF        clear_buffer_i_L0+1, 1 
;USB.c,53 :: 		}
	GOTO        L_clear_buffer2
L_clear_buffer3:
;USB.c,54 :: 		}
L_end_clear_buffer:
	RETURN      0
; end of _clear_buffer

_main:

;USB.c,56 :: 		void main()
;USB.c,58 :: 		UART1_Init(9600);
	MOVLW       129
	MOVWF       SPBRG+0 
	BSF         TXSTA+0, 2, 0
	CALL        _UART1_Init+0, 0
;USB.c,59 :: 		Delay_ms(100);
	MOVLW       3
	MOVWF       R11, 0
	MOVLW       138
	MOVWF       R12, 0
	MOVLW       85
	MOVWF       R13, 0
L_main4:
	DECFSZ      R13, 1, 1
	BRA         L_main4
	DECFSZ      R12, 1, 1
	BRA         L_main4
	DECFSZ      R11, 1, 1
	BRA         L_main4
	NOP
	NOP
;USB.c,60 :: 		UART1_Write_Text_Newline("USB Test Program");
	MOVLW       ?lstr1_USB+0
	MOVWF       FARG_UART1_Write_Text_Newline_msg+0 
	MOVLW       hi_addr(?lstr1_USB+0)
	MOVWF       FARG_UART1_Write_Text_Newline_msg+1 
	CALL        _UART1_Write_Text_Newline+0, 0
;USB.c,62 :: 		ADCON1 |= 0x0F;          // Configure AN pins as digital
	MOVLW       15
	IORWF       ADCON1+0, 1 
;USB.c,63 :: 		CMCON |= 7;            // Disable comparators
	MOVLW       7
	IORWF       CMCON+0, 1 
;USB.c,64 :: 		TRISA = 0x00;
	CLRF        TRISA+0 
;USB.c,65 :: 		TRISB = 0x00;
	CLRF        TRISB+0 
;USB.c,66 :: 		TRISC = 0x80;
	MOVLW       128
	MOVWF       TRISC+0 
;USB.c,67 :: 		PORTB.B0 = 0;
	BCF         PORTB+0, 0 
;USB.c,69 :: 		Lcd_Init();            // Initialize Lcd8
	CALL        _Lcd_Init+0, 0
;USB.c,70 :: 		Delay_ms(100);
	MOVLW       3
	MOVWF       R11, 0
	MOVLW       138
	MOVWF       R12, 0
	MOVLW       85
	MOVWF       R13, 0
L_main5:
	DECFSZ      R13, 1, 1
	BRA         L_main5
	DECFSZ      R12, 1, 1
	BRA         L_main5
	DECFSZ      R11, 1, 1
	BRA         L_main5
	NOP
	NOP
;USB.c,71 :: 		Lcd_Cmd(_LCD_CLEAR);       // Clear display
	MOVLW       1
	MOVWF       FARG_Lcd_Cmd_out_char+0 
	CALL        _Lcd_Cmd+0, 0
;USB.c,72 :: 		Delay_ms(100);
	MOVLW       3
	MOVWF       R11, 0
	MOVLW       138
	MOVWF       R12, 0
	MOVLW       85
	MOVWF       R13, 0
L_main6:
	DECFSZ      R13, 1, 1
	BRA         L_main6
	DECFSZ      R12, 1, 1
	BRA         L_main6
	DECFSZ      R11, 1, 1
	BRA         L_main6
	NOP
	NOP
;USB.c,73 :: 		Lcd_Cmd(_LCD_CURSOR_OFF);     // Cursor off
	MOVLW       12
	MOVWF       FARG_Lcd_Cmd_out_char+0 
	CALL        _Lcd_Cmd+0, 0
;USB.c,74 :: 		Delay_ms(100);
	MOVLW       3
	MOVWF       R11, 0
	MOVLW       138
	MOVWF       R12, 0
	MOVLW       85
	MOVWF       R13, 0
L_main7:
	DECFSZ      R13, 1, 1
	BRA         L_main7
	DECFSZ      R12, 1, 1
	BRA         L_main7
	DECFSZ      R11, 1, 1
	BRA         L_main7
	NOP
	NOP
;USB.c,75 :: 		Lcd_Out(1,3,"PIC18F4550");        // Write text in first row
	MOVLW       1
	MOVWF       FARG_Lcd_Out_row+0 
	MOVLW       3
	MOVWF       FARG_Lcd_Out_column+0 
	MOVLW       ?lstr2_USB+0
	MOVWF       FARG_Lcd_Out_text+0 
	MOVLW       hi_addr(?lstr2_USB+0)
	MOVWF       FARG_Lcd_Out_text+1 
	CALL        _Lcd_Out+0, 0
;USB.c,76 :: 		Delay_ms(100);
	MOVLW       3
	MOVWF       R11, 0
	MOVLW       138
	MOVWF       R12, 0
	MOVLW       85
	MOVWF       R13, 0
L_main8:
	DECFSZ      R13, 1, 1
	BRA         L_main8
	DECFSZ      R12, 1, 1
	BRA         L_main8
	DECFSZ      R11, 1, 1
	BRA         L_main8
	NOP
	NOP
;USB.c,77 :: 		Lcd_Out(2,3,"USB Example!");        // Write text in second row
	MOVLW       2
	MOVWF       FARG_Lcd_Out_row+0 
	MOVLW       3
	MOVWF       FARG_Lcd_Out_column+0 
	MOVLW       ?lstr3_USB+0
	MOVWF       FARG_Lcd_Out_text+0 
	MOVLW       hi_addr(?lstr3_USB+0)
	MOVWF       FARG_Lcd_Out_text+1 
	CALL        _Lcd_Out+0, 0
;USB.c,78 :: 		Delay_ms(2000);
	MOVLW       51
	MOVWF       R11, 0
	MOVLW       187
	MOVWF       R12, 0
	MOVLW       223
	MOVWF       R13, 0
L_main9:
	DECFSZ      R13, 1, 1
	BRA         L_main9
	DECFSZ      R12, 1, 1
	BRA         L_main9
	DECFSZ      R11, 1, 1
	BRA         L_main9
	NOP
	NOP
;USB.c,80 :: 		INTCON = 0;
	CLRF        INTCON+0 
;USB.c,81 :: 		INTCON2 = 0xF5;
	MOVLW       245
	MOVWF       INTCON2+0 
;USB.c,82 :: 		INTCON3 = 0xC0;
	MOVLW       192
	MOVWF       INTCON3+0 
;USB.c,83 :: 		RCON.IPEN = 0;
	BCF         RCON+0, 7 
;USB.c,84 :: 		PIE1 = 0;
	CLRF        PIE1+0 
;USB.c,85 :: 		PIE2 = 0;
	CLRF        PIE2+0 
;USB.c,86 :: 		PIR1 = 0;
	CLRF        PIR1+0 
;USB.c,87 :: 		PIR2 = 0;
	CLRF        PIR2+0 
;USB.c,96 :: 		T0CON = 0x47; // Prescaler = 256
	MOVLW       71
	MOVWF       T0CON+0 
;USB.c,97 :: 		TMR0L = 100; // Timer count is 256-156 = 100
	MOVLW       100
	MOVWF       TMR0L+0 
;USB.c,98 :: 		INTCON.TMR0IE = 1; // Enable T0IE
	BSF         INTCON+0, 5 
;USB.c,99 :: 		T0CON.TMR0ON = 1; // Turn Timer 0 ON
	BSF         T0CON+0, 7 
;USB.c,100 :: 		INTCON = 0xE0; // Enable interrupts
	MOVLW       224
	MOVWF       INTCON+0 
;USB.c,106 :: 		if(PORTA.B0 == 1)
	BTFSS       PORTA+0, 0 
	GOTO        L_main10
;USB.c,108 :: 		UART1_Write_Text_Newline("Data is Ready to be Received from the PC");
	MOVLW       ?lstr4_USB+0
	MOVWF       FARG_UART1_Write_Text_Newline_msg+0 
	MOVLW       hi_addr(?lstr4_USB+0)
	MOVWF       FARG_UART1_Write_Text_Newline_msg+1 
	CALL        _UART1_Write_Text_Newline+0, 0
;USB.c,109 :: 		Hid_Enable(&Read_Buffer,&Write_Buffer);
	MOVLW       _Read_Buffer+0
	MOVWF       FARG_HID_Enable_readbuff+0 
	MOVLW       hi_addr(_Read_Buffer+0)
	MOVWF       FARG_HID_Enable_readbuff+1 
	MOVLW       _Write_Buffer+0
	MOVWF       FARG_HID_Enable_writebuff+0 
	MOVLW       hi_addr(_Write_Buffer+0)
	MOVWF       FARG_HID_Enable_writebuff+1 
	CALL        _HID_Enable+0, 0
;USB.c,110 :: 		UART1_Write_Text_Newline("USB connected, waiting for enumeration...");
	MOVLW       ?lstr5_USB+0
	MOVWF       FARG_UART1_Write_Text_Newline_msg+0 
	MOVLW       hi_addr(?lstr5_USB+0)
	MOVWF       FARG_UART1_Write_Text_Newline_msg+1 
	CALL        _UART1_Write_Text_Newline+0, 0
;USB.c,111 :: 		Delay_ms(2000);
	MOVLW       51
	MOVWF       R11, 0
	MOVLW       187
	MOVWF       R12, 0
	MOVLW       223
	MOVWF       R13, 0
L_main11:
	DECFSZ      R13, 1, 1
	BRA         L_main11
	DECFSZ      R12, 1, 1
	BRA         L_main11
	DECFSZ      R11, 1, 1
	BRA         L_main11
	NOP
	NOP
;USB.c,112 :: 		UART1_Write_Text_Newline("OK");
	MOVLW       ?lstr6_USB+0
	MOVWF       FARG_UART1_Write_Text_Newline_msg+0 
	MOVLW       hi_addr(?lstr6_USB+0)
	MOVWF       FARG_UART1_Write_Text_Newline_msg+1 
	CALL        _UART1_Write_Text_Newline+0, 0
;USB.c,113 :: 		PORTB.B0 = 1;
	BSF         PORTB+0, 0 
;USB.c,114 :: 		}
L_main10:
;USB.c,118 :: 		start:
___main_start:
;USB.c,120 :: 		while(Hid_Read() == 0);  //Stay Here if Data is Not Coming from Serial Port
L_main12:
	CALL        _HID_Read+0, 0
	MOVF        R0, 0 
	XORLW       0
	BTFSS       STATUS+0, 2 
	GOTO        L_main13
	GOTO        L_main12
L_main13:
;USB.c,122 :: 		if(strncmp(Read_Buffer,"S",1) == 0)
	MOVLW       _Read_Buffer+0
	MOVWF       FARG_strncmp_s1+0 
	MOVLW       hi_addr(_Read_Buffer+0)
	MOVWF       FARG_strncmp_s1+1 
	MOVLW       ?lstr7_USB+0
	MOVWF       FARG_strncmp_s2+0 
	MOVLW       hi_addr(?lstr7_USB+0)
	MOVWF       FARG_strncmp_s2+1 
	MOVLW       1
	MOVWF       FARG_strncmp_len+0 
	CALL        _strncmp+0, 0
	MOVLW       0
	XORWF       R1, 0 
	BTFSS       STATUS+0, 2 
	GOTO        L__main30
	MOVLW       0
	XORWF       R0, 0 
L__main30:
	BTFSS       STATUS+0, 2 
	GOTO        L_main14
;USB.c,124 :: 		Lcd_Cmd(_LCD_CLEAR);
	MOVLW       1
	MOVWF       FARG_Lcd_Cmd_out_char+0 
	CALL        _Lcd_Cmd+0, 0
;USB.c,125 :: 		Lcd_Out(1,2,"Authentication");
	MOVLW       1
	MOVWF       FARG_Lcd_Out_row+0 
	MOVLW       2
	MOVWF       FARG_Lcd_Out_column+0 
	MOVLW       ?lstr8_USB+0
	MOVWF       FARG_Lcd_Out_text+0 
	MOVLW       hi_addr(?lstr8_USB+0)
	MOVWF       FARG_Lcd_Out_text+1 
	CALL        _Lcd_Out+0, 0
;USB.c,126 :: 		Lcd_Out(2,8,"OK");
	MOVLW       2
	MOVWF       FARG_Lcd_Out_row+0 
	MOVLW       8
	MOVWF       FARG_Lcd_Out_column+0 
	MOVLW       ?lstr9_USB+0
	MOVWF       FARG_Lcd_Out_text+0 
	MOVLW       hi_addr(?lstr9_USB+0)
	MOVWF       FARG_Lcd_Out_text+1 
	CALL        _Lcd_Out+0, 0
;USB.c,127 :: 		UART1_Write_Text_Newline("USB Authentication - OK");
	MOVLW       ?lstr10_USB+0
	MOVWF       FARG_UART1_Write_Text_Newline_msg+0 
	MOVLW       hi_addr(?lstr10_USB+0)
	MOVWF       FARG_UART1_Write_Text_Newline_msg+1 
	CALL        _UART1_Write_Text_Newline+0, 0
;USB.c,128 :: 		UART1_Write_Text_Newline("USB enumaration by PC/HOST");
	MOVLW       ?lstr11_USB+0
	MOVWF       FARG_UART1_Write_Text_Newline_msg+0 
	MOVLW       hi_addr(?lstr11_USB+0)
	MOVWF       FARG_UART1_Write_Text_Newline_msg+1 
	CALL        _UART1_Write_Text_Newline+0, 0
;USB.c,130 :: 		goto loop;
	GOTO        ___main_loop
;USB.c,131 :: 		}
L_main14:
;USB.c,134 :: 		Lcd_Cmd(_LCD_CLEAR);
	MOVLW       1
	MOVWF       FARG_Lcd_Cmd_out_char+0 
	CALL        _Lcd_Cmd+0, 0
;USB.c,135 :: 		Lcd_Out(1,2,"Authentication");
	MOVLW       1
	MOVWF       FARG_Lcd_Out_row+0 
	MOVLW       2
	MOVWF       FARG_Lcd_Out_column+0 
	MOVLW       ?lstr12_USB+0
	MOVWF       FARG_Lcd_Out_text+0 
	MOVLW       hi_addr(?lstr12_USB+0)
	MOVWF       FARG_Lcd_Out_text+1 
	CALL        _Lcd_Out+0, 0
;USB.c,136 :: 		Lcd_Out(2,5,"Fails!");
	MOVLW       2
	MOVWF       FARG_Lcd_Out_row+0 
	MOVLW       5
	MOVWF       FARG_Lcd_Out_column+0 
	MOVLW       ?lstr13_USB+0
	MOVWF       FARG_Lcd_Out_text+0 
	MOVLW       hi_addr(?lstr13_USB+0)
	MOVWF       FARG_Lcd_Out_text+1 
	CALL        _Lcd_Out+0, 0
;USB.c,137 :: 		UART1_Write_Text_Newline("USB Authentication - Fails!");
	MOVLW       ?lstr14_USB+0
	MOVWF       FARG_UART1_Write_Text_Newline_msg+0 
	MOVLW       hi_addr(?lstr14_USB+0)
	MOVWF       FARG_UART1_Write_Text_Newline_msg+1 
	CALL        _UART1_Write_Text_Newline+0, 0
;USB.c,139 :: 		goto start;
	GOTO        ___main_start
;USB.c,142 :: 		loop:
___main_loop:
;USB.c,146 :: 		Delay_ms(1000);
	MOVLW       26
	MOVWF       R11, 0
	MOVLW       94
	MOVWF       R12, 0
	MOVLW       110
	MOVWF       R13, 0
L_main16:
	DECFSZ      R13, 1, 1
	BRA         L_main16
	DECFSZ      R12, 1, 1
	BRA         L_main16
	DECFSZ      R11, 1, 1
	BRA         L_main16
	NOP
;USB.c,147 :: 		Lcd_Cmd(_LCD_CLEAR);
	MOVLW       1
	MOVWF       FARG_Lcd_Cmd_out_char+0 
	CALL        _Lcd_Cmd+0, 0
;USB.c,148 :: 		Lcd_Out(1,1,"Received Data-");
	MOVLW       1
	MOVWF       FARG_Lcd_Out_row+0 
	MOVLW       1
	MOVWF       FARG_Lcd_Out_column+0 
	MOVLW       ?lstr15_USB+0
	MOVWF       FARG_Lcd_Out_text+0 
	MOVLW       hi_addr(?lstr15_USB+0)
	MOVWF       FARG_Lcd_Out_text+1 
	CALL        _Lcd_Out+0, 0
;USB.c,149 :: 		flag = 0;
	CLRF        _flag+0 
;USB.c,151 :: 		loop_second:
___main_loop_second:
;USB.c,153 :: 		clear_buffer(Read_Buffer);
	MOVLW       _Read_Buffer+0
	MOVWF       FARG_clear_buffer_buffer+0 
	MOVLW       hi_addr(_Read_Buffer+0)
	MOVWF       FARG_clear_buffer_buffer+1 
	CALL        _clear_buffer+0, 0
;USB.c,154 :: 		while(Hid_Read() == 0)
L_main17:
	CALL        _HID_Read+0, 0
	MOVF        R0, 0 
	XORLW       0
	BTFSS       STATUS+0, 2 
	GOTO        L_main18
;USB.c,156 :: 		if(flag == 0)
	MOVF        _flag+0, 0 
	XORLW       0
	BTFSS       STATUS+0, 2 
	GOTO        L_main19
;USB.c,158 :: 		Lcd_Out(2,1,"No Data");
	MOVLW       2
	MOVWF       FARG_Lcd_Out_row+0 
	MOVLW       1
	MOVWF       FARG_Lcd_Out_column+0 
	MOVLW       ?lstr16_USB+0
	MOVWF       FARG_Lcd_Out_text+0 
	MOVLW       hi_addr(?lstr16_USB+0)
	MOVWF       FARG_Lcd_Out_text+1 
	CALL        _Lcd_Out+0, 0
;USB.c,159 :: 		flag = 1;
	MOVLW       1
	MOVWF       _flag+0 
;USB.c,160 :: 		}
L_main19:
;USB.c,161 :: 		}
	GOTO        L_main17
L_main18:
;USB.c,163 :: 		Lcd_Cmd(_LCD_CLEAR);
	MOVLW       1
	MOVWF       FARG_Lcd_Cmd_out_char+0 
	CALL        _Lcd_Cmd+0, 0
;USB.c,164 :: 		Lcd_Out(1,1,"Received Data-");
	MOVLW       1
	MOVWF       FARG_Lcd_Out_row+0 
	MOVLW       1
	MOVWF       FARG_Lcd_Out_column+0 
	MOVLW       ?lstr17_USB+0
	MOVWF       FARG_Lcd_Out_text+0 
	MOVLW       hi_addr(?lstr17_USB+0)
	MOVWF       FARG_Lcd_Out_text+1 
	CALL        _Lcd_Out+0, 0
;USB.c,165 :: 		Lcd_Out(2,1,Read_Buffer);
	MOVLW       2
	MOVWF       FARG_Lcd_Out_row+0 
	MOVLW       1
	MOVWF       FARG_Lcd_Out_column+0 
	MOVLW       _Read_Buffer+0
	MOVWF       FARG_Lcd_Out_text+0 
	MOVLW       hi_addr(_Read_Buffer+0)
	MOVWF       FARG_Lcd_Out_text+1 
	CALL        _Lcd_Out+0, 0
;USB.c,167 :: 		strcpy(Write_Buffer, "Received Data-");
	MOVLW       _Write_Buffer+0
	MOVWF       FARG_strcpy_to+0 
	MOVLW       hi_addr(_Write_Buffer+0)
	MOVWF       FARG_strcpy_to+1 
	MOVLW       ?lstr18_USB+0
	MOVWF       FARG_strcpy_from+0 
	MOVLW       hi_addr(?lstr18_USB+0)
	MOVWF       FARG_strcpy_from+1 
	CALL        _strcpy+0, 0
;USB.c,168 :: 		strcat(Write_Buffer, Read_Buffer);
	MOVLW       _Write_Buffer+0
	MOVWF       FARG_strcat_to+0 
	MOVLW       hi_addr(_Write_Buffer+0)
	MOVWF       FARG_strcat_to+1 
	MOVLW       _Read_Buffer+0
	MOVWF       FARG_strcat_from+0 
	MOVLW       hi_addr(_Read_Buffer+0)
	MOVWF       FARG_strcat_from+1 
	CALL        _strcat+0, 0
;USB.c,169 :: 		while(!HID_Write(&Write_Buffer, 64)) ;
L_main20:
	MOVLW       _Write_Buffer+0
	MOVWF       FARG_HID_Write_writebuff+0 
	MOVLW       hi_addr(_Write_Buffer+0)
	MOVWF       FARG_HID_Write_writebuff+1 
	MOVLW       64
	MOVWF       FARG_HID_Write_len+0 
	CALL        _HID_Write+0, 0
	MOVF        R0, 1 
	BTFSS       STATUS+0, 2 
	GOTO        L_main21
	GOTO        L_main20
L_main21:
;USB.c,170 :: 		UART1_Write_Text_Newline(Write_Buffer);
	MOVLW       _Write_Buffer+0
	MOVWF       FARG_UART1_Write_Text_Newline_msg+0 
	MOVLW       hi_addr(_Write_Buffer+0)
	MOVWF       FARG_UART1_Write_Text_Newline_msg+1 
	CALL        _UART1_Write_Text_Newline+0, 0
;USB.c,171 :: 		clrUSB();
	CALL        _clrUSB+0, 0
;USB.c,173 :: 		if(strncmp(Read_Buffer,"Disable",1) == 0)
	MOVLW       _Read_Buffer+0
	MOVWF       FARG_strncmp_s1+0 
	MOVLW       hi_addr(_Read_Buffer+0)
	MOVWF       FARG_strncmp_s1+1 
	MOVLW       ?lstr19_USB+0
	MOVWF       FARG_strncmp_s2+0 
	MOVLW       hi_addr(?lstr19_USB+0)
	MOVWF       FARG_strncmp_s2+1 
	MOVLW       1
	MOVWF       FARG_strncmp_len+0 
	CALL        _strncmp+0, 0
	MOVLW       0
	XORWF       R1, 0 
	BTFSS       STATUS+0, 2 
	GOTO        L__main31
	MOVLW       0
	XORWF       R0, 0 
L__main31:
	BTFSS       STATUS+0, 2 
	GOTO        L_main22
;USB.c,175 :: 		Lcd_Cmd(_LCD_CLEAR);
	MOVLW       1
	MOVWF       FARG_Lcd_Cmd_out_char+0 
	CALL        _Lcd_Cmd+0, 0
;USB.c,176 :: 		goto end;
	GOTO        ___main_end
;USB.c,177 :: 		}
L_main22:
;USB.c,179 :: 		goto loop_second;
	GOTO        ___main_loop_second
;USB.c,181 :: 		end:
___main_end:
;USB.c,183 :: 		UART1_Write_Text_Newline("USB disconnected, waiting for connection...");
	MOVLW       ?lstr20_USB+0
	MOVWF       FARG_UART1_Write_Text_Newline_msg+0 
	MOVLW       hi_addr(?lstr20_USB+0)
	MOVWF       FARG_UART1_Write_Text_Newline_msg+1 
	CALL        _UART1_Write_Text_Newline+0, 0
;USB.c,184 :: 		Delay_ms(1000);
	MOVLW       26
	MOVWF       R11, 0
	MOVLW       94
	MOVWF       R12, 0
	MOVLW       110
	MOVWF       R13, 0
L_main23:
	DECFSZ      R13, 1, 1
	BRA         L_main23
	DECFSZ      R12, 1, 1
	BRA         L_main23
	DECFSZ      R11, 1, 1
	BRA         L_main23
	NOP
;USB.c,185 :: 		Hid_Disable();
	CALL        _HID_Disable+0, 0
;USB.c,186 :: 		Lcd_Out(1,1,"HID DISABLE");
	MOVLW       1
	MOVWF       FARG_Lcd_Out_row+0 
	MOVLW       1
	MOVWF       FARG_Lcd_Out_column+0 
	MOVLW       ?lstr21_USB+0
	MOVWF       FARG_Lcd_Out_text+0 
	MOVLW       hi_addr(?lstr21_USB+0)
	MOVWF       FARG_Lcd_Out_text+1 
	CALL        _Lcd_Out+0, 0
;USB.c,187 :: 		UART1_Write_Text_Newline("OK");
	MOVLW       ?lstr22_USB+0
	MOVWF       FARG_UART1_Write_Text_Newline_msg+0 
	MOVLW       hi_addr(?lstr22_USB+0)
	MOVWF       FARG_UART1_Write_Text_Newline_msg+1 
	CALL        _UART1_Write_Text_Newline+0, 0
;USB.c,188 :: 		PORTB.B0 = 0;
	BCF         PORTB+0, 0 
;USB.c,189 :: 		}
L_end_main:
	GOTO        $+0
; end of _main
