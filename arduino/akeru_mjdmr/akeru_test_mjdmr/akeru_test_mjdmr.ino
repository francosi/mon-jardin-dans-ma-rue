#include <Akeru.h>
#include <SoftwareSerial.h>

#include <util.h>

#include <SPI.h>

void setup() {
  Serial.begin(9600);

}

void loop() {  
  Akeru.begin();
   char myFloatStream[] = "humidity";
  
    if (Serial.available()>=21){ // If Ar sees datas on Rx
      while(Serial.available()){ // While there data
        if(Serial.read() == 0x7E){ // Begining checks
          if(Serial.read() == 0x00){
           for(int i=0; i<17; i++){
               Serial.read();  // Move the "cursor" to the right location
           }
           byte msb = Serial.read(); // First half of the required data
           byte lsb = Serial.read(); // Second half of the required data

           int tmp = msb<<8 | lsb; // Entire data
           double tfloat = tmp;  // Data processing
           tfloat = tfloat * 100;
           tfloat = tfloat/0x3FF; // Number to humidity %
           
           
           Serial.print("H(%): ");Serial.println(tfloat); // Print on the monitor "H(%):sensor data"
           //msb=0x03;lsb=0xFF;
           char test [2]= {msb, lsb};
           boolean ets = Akeru.send(&test, 2*sizeof(char)); // Send the value to Sigfox cloud
           //Serial.println(ets); // Display if the data has been sent or not
          }
        }
      }
     }
  delay(10); // 10ms delay
}
