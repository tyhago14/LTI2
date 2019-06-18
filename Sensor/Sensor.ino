#include<Wire.h>
#include<MPU6050.h>


int PAH = 200;
int NSH = 5;
int PMH = 3000;


int ISS = 1;
const int MPU_addr = 0x68; // I2C address of the MPU-6050
int16_t Tmp, GyX, GyY, GyZ;
float AcX, AcY, AcZ, Ax, Ay, Az;

int minVal = 265;
int maxVal = 402;
MPU6050 mpu;
bool recebeu =  false;
bool raw = false;
String start ;

void setup() {
  Wire.begin();
  Wire.beginTransmission(MPU_addr);
  Wire.write(0x6B);  // PWR_MGMT_1 register
  Wire.write(0);     // set to zero (wakes up the MPU-6050)
  Wire.endTransmission(true);
  Serial.begin(9600);
           mpu.setFullScaleAccelRange(MPU6050_ACCEL_FS_4);
  
}

String NrTramas;
String tempoCorrer;
String tempoPausa;
String TP;
String t_seconds;

int NrTramasInt;
int tempoCorrerInt;
long tempoCorrerInt2;
int tempoPausaInt;

long t_s;
int t_mi;
int tempo_total;
long Soma_segundos;
int Soma_mill;

String superString;
bool INFO = false;

void loop() {

  
  //if(INFO == false){Serial.print("~ 1");delay(50); }
  
  if (Serial.available() > 0 ) {
    INFO = true;
    TP = Serial.readStringUntil(' ');
    if (TP.equals("3") || TP.equals("5") ) {

      if (TP.equals("5"))
      {
        raw = true;       // verifica se foi um pedido RAW
      }
      else
      {
        raw = false;
      }

      recebeu = false;
      NrTramas = Serial.readStringUntil(' ');
      tempoCorrer = Serial.readStringUntil(' ');
      tempoPausa = Serial.readStringUntil(' ');
      t_seconds = Serial.readStringUntil('\n');

      NrTramasInt = NrTramas.toInt();
      tempoCorrerInt = tempoCorrer.toInt();
      tempoCorrerInt2 = tempoCorrer.toInt();
      tempoPausaInt = tempoPausa.toInt();
      t_s = t_seconds.toInt();

      tempo_total = (NrTramasInt * tempoCorrerInt2);          //DESCOBRE OS SEGUNDOS//
      Soma_segundos = ((tempo_total / 1000 ) + t_s);

      Soma_mill = tempo_total % 1000 ;                        //DESCOBRE OS MILLIS//



      if (NrTramasInt == 0)
      {
        String ErrorString;
        ErrorString = "~2";
        ErrorString = ErrorString + ' ' + ISS;
        ErrorString = ErrorString + ' ' + Soma_segundos;
        ErrorString = ErrorString + '.' + Soma_mill;
        ErrorString = ErrorString + ' ' + '1';
        Serial.println(ErrorString);
        NrTramasInt = NSH;
      }

      if (tempoCorrerInt < 199)
      {
        String ErrorString;
        ErrorString = "~2";
        ErrorString = ErrorString + ' ' + ISS;
        ErrorString = ErrorString + ' ' + Soma_segundos;
        ErrorString = ErrorString + '.' + Soma_mill;
        ErrorString = ErrorString + ' ' + '2';
        Serial.println(ErrorString);
        tempoCorrerInt = PAH;
      }

      if (tempoPausaInt < (NrTramasInt * tempoCorrerInt))
      {
        String ErrorString;
        ErrorString = "~2";
        ErrorString = ErrorString + ' ' + ISS;
        ErrorString = ErrorString + ' ' + Soma_segundos;
        ErrorString = ErrorString + '.' + Soma_mill;
        ErrorString = ErrorString + ' ' + '3';
        Serial.println(ErrorString);
        tempoPausaInt = PMH;
      }

      while (recebeu == false) {

        int NrAmostras = NrTramasInt;

        superString = "~1 " ;
        superString = superString + ISS;
        superString = superString + ' ' + Soma_segundos;
        superString = superString + '.' + Soma_mill;
        superString = superString + ' ' + tempoPausaInt;
        superString = superString + ' ' + tempoCorrerInt;
        superString = superString + ' ' + NrTramasInt;

        while (NrAmostras != 0) {

          Wire.beginTransmission(MPU_addr);
          Wire.write(0x3B);
          Wire.endTransmission(false);
          Wire.requestFrom(MPU_addr, 14, true);

          AcX = Wire.read() << 8 | Wire.read();
          AcY = Wire.read() << 8 | Wire.read();
          AcZ = Wire.read() << 8 | Wire.read();
          Tmp = Wire.read() << 8 | Wire.read();
          GyX = Wire.read() << 8 | Wire.read();
          GyY = Wire.read() << 8 | Wire.read();
          GyZ = Wire.read() << 8 | Wire.read();

          float tempReal;
          float AcXtrue;
          float AcYtrue;
          float AcZtrue;
          int x;
          int y;
          int z;



          if (raw == true)
          {
            tempReal = Tmp;
            AcXtrue = AcX;
            AcYtrue = AcY;
            AcZtrue = AcZ;

            x = GyX;
            y = GyY;
            z = GyZ;

          }

          else
          {
            tempReal = Tmp / 340.00 + 36.53;
            AcXtrue = ((AcX) / 8192.00);
            AcYtrue = ((AcY) / 8192.00);
            AcZtrue = ((AcZ) / 8192.00);

            int xAng = map(AcX, minVal, maxVal, -90, 90);
            int yAng = map(AcY, minVal, maxVal, -90, 90);
            int zAng = map(AcZ, minVal, maxVal, -90, 90);

            x = (RAD_TO_DEG * (atan2(-yAng, -zAng) + PI));
            y = (RAD_TO_DEG * (atan2(-xAng, -zAng) + PI));
            z = (RAD_TO_DEG * (atan2(-yAng, -xAng) + PI));
          }

          if ( x == 225 && y == 225 && z == 225 || (x == -1 && y == -1 && z == -1 )) // comparar os outros valores nao funciona
          {
            String ErrorString;
            ErrorString = "~2";
            ErrorString = ErrorString + ' ' + ISS;
            ErrorString = ErrorString + ' ' + Soma_segundos;
            ErrorString = ErrorString + '.' + Soma_mill;
            ErrorString = ErrorString + ' ' + '4';
            Serial.println(ErrorString);
            recebeu = true;
            NrAmostras = 0;
          }

          else
          {
            superString = superString + ' ' + AcXtrue;
            superString = superString + ' ' + AcYtrue;
            superString = superString + ' ' + AcZtrue;
            superString = superString + ' ' + x;
            superString = superString + ' ' + y;
            superString = superString + ' ' + z;
            superString = superString + ' ' + tempReal;
            NrAmostras--;
            delay(tempoCorrerInt);
          }
        }

        //float tempo = millis();
        //Serial.print(tempo);
        Serial.println(superString);

        if (Serial.available() > 0 ) {
          TP = Serial.readStringUntil('\n');
          String ComandoStop;
          if (TP.equals("4")) {
            recebeu = true;
          }
        }
        tempo_total = (tempoPausaInt);
        Soma_segundos = ((tempo_total / 1000 ) + Soma_segundos);
        int resto  = tempo_total % 1000 ;

        if (Soma_mill + resto >= 1000)
        {
          Soma_segundos++;
          Soma_mill = (Soma_mill + resto) - 1000;
        }
        else
        {
          Soma_mill = Soma_mill + resto;
        }

        int delayespecial = (tempoPausaInt - (tempoCorrerInt * NrTramasInt));
        delay(delayespecial);

      }
    }
  }
}
