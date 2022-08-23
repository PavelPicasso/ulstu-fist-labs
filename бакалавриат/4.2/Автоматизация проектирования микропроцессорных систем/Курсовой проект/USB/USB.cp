#line 1 "C:/Users/Pavel/Documents/USB.c"
unsigned char Read_Buffer[64] absolute 0x500;
unsigned char Write_Buffer[64]absolute 0x510;
unsigned char num,flag;

void interrupt()
{
 USB_Interrupt_Proc();
 TMR0L = 100;
 INTCON.TMR0IF = 0;
}

void clrUSB()
{
 memset(Write_Buffer, 0, sizeof Write_Buffer);
 while(!HID_Write(&Write_Buffer, sizeof Write_Buffer));
}


sbit LCD_RS at RC0_bit;
sbit LCD_RW at RC1_bit;
sbit LCD_EN at RC2_bit;
sbit LCD_D7 at RD7_bit;
sbit LCD_D6 at RD6_bit;
sbit LCD_D5 at RD5_bit;
sbit LCD_D4 at RD4_bit;
sbit LCD_D3 at RD3_bit;
sbit LCD_RS_Direction at TRISC0_bit;
sbit LCD_RW_Direction at TRISC1_bit;
sbit LCD_EN_Direction at TRISC2_bit;
sbit LCD_D7_Direction at TRISD7_bit;
sbit LCD_D6_Direction at TRISD6_bit;
sbit LCD_D5_Direction at TRISD5_bit;
sbit LCD_D4_Direction at TRISD4_bit;
sbit LCD_D3_Direction at TRISD3_bit;


char i;

void UART1_Write_Text_Newline(unsigned char msg[])
{
 UART1_Write_Text(msg);
 UART1_Write(10);
 UART1_Write(13);
}

void clear_buffer(unsigned char buffer[])
{
 unsigned int i = 0;
 while(buffer[i] != '\0')
 {
 buffer[i] = '\0';
 i++;
 }
}

void main()
{
 UART1_Init(9600);
 Delay_ms(100);
 UART1_Write_Text_Newline("USB Test Program");

 ADCON1 |= 0x0F;
 CMCON |= 7;
 TRISA = 0x00;
 TRISB = 0x00;
 TRISC = 0x80;
 PORTB.B0 = 0;

 Lcd_Init();
 Delay_ms(100);
 Lcd_Cmd(_LCD_CLEAR);
 Delay_ms(100);
 Lcd_Cmd(_LCD_CURSOR_OFF);
 Delay_ms(100);
 Lcd_Out(1,3,"PIC18F4550");
 Delay_ms(100);
 Lcd_Out(2,3,"USB Example!");
 Delay_ms(2000);

 INTCON = 0;
 INTCON2 = 0xF5;
 INTCON3 = 0xC0;
 RCON.IPEN = 0;
 PIE1 = 0;
 PIE2 = 0;
 PIR1 = 0;
 PIR2 = 0;








 T0CON = 0x47;
 TMR0L = 100;
 INTCON.TMR0IE = 1;
 T0CON.TMR0ON = 1;
 INTCON = 0xE0;





 if(PORTA.B0 == 1)
 {
 UART1_Write_Text_Newline("Data is Ready to be Received from the PC");
 Hid_Enable(&Read_Buffer,&Write_Buffer);
 UART1_Write_Text_Newline("USB connected, waiting for enumeration...");
 Delay_ms(2000);
 UART1_Write_Text_Newline("OK");
 PORTB.B0 = 1;
 }



 start:

 while(Hid_Read() == 0);

 if(strncmp(Read_Buffer,"S",1) == 0)
 {
 Lcd_Cmd(_LCD_CLEAR);
 Lcd_Out(1,2,"Authentication");
 Lcd_Out(2,8,"OK");
 UART1_Write_Text_Newline("USB Authentication - OK");
 UART1_Write_Text_Newline("USB enumaration by PC/HOST");

 goto loop;
 }
 else
 {
 Lcd_Cmd(_LCD_CLEAR);
 Lcd_Out(1,2,"Authentication");
 Lcd_Out(2,5,"Fails!");
 UART1_Write_Text_Newline("USB Authentication - Fails!");

 goto start;
 }

 loop:



 Delay_ms(1000);
 Lcd_Cmd(_LCD_CLEAR);
 Lcd_Out(1,1,"Received Data-");
 flag = 0;

 loop_second:

 clear_buffer(Read_Buffer);
 while(Hid_Read() == 0)
 {
 if(flag == 0)
 {
 Lcd_Out(2,1,"No Data");
 flag = 1;
 }
 }

 Lcd_Cmd(_LCD_CLEAR);
 Lcd_Out(1,1,"Received Data-");
 Lcd_Out(2,1,Read_Buffer);

 strcpy(Write_Buffer, "Received Data-");
 strcat(Write_Buffer, Read_Buffer);
 while(!HID_Write(&Write_Buffer, 64)) ;
 UART1_Write_Text_Newline(Write_Buffer);
 clrUSB();

 if(strncmp(Read_Buffer,"Disable",1) == 0)
 {
 Lcd_Cmd(_LCD_CLEAR);
 goto end;
 }

 goto loop_second;

 end:

 UART1_Write_Text_Newline("USB disconnected, waiting for connection...");
 Delay_ms(1000);
 Hid_Disable();
 Lcd_Out(1,1,"HID DISABLE");
 UART1_Write_Text_Newline("OK");
 PORTB.B0 = 0;
}
