#include <stdio.h>
#include <unistd.h>
#include <stdlib.h>
#include <string.h>
using namespace std;
#include <string>
#include <sys/types.h>
#include <fstream>
#include <winsock.h>
#include <winsock2.h>
#include <math.h>
#include <sstream>
#include <thread>         // std::thread
#include <cstdio>
#include <ctime>
#include <windows.h>
#include <iostream>
#include <conio.h>
#include <signal.h>
#include <mysql.h>

#define DEFAULT_HELPER_EXE  "ConsoleLoggerHelper.exe"

    string areasubject[6][6];

    WSADATA WSAData;

    SOCKET server, client;

    SOCKADDR_IN serverAddr, clientAddr;


    double thisAcX, thisAcY, thisAcZ;
    double lastAcX, lastAcY, lastAcZ;

    ofstream logfile ("log.txt",fstream::app);
    ofstream errorfile ("error.txt",fstream::app);


    char buffer[1024];

      time_t rawtime;
      struct tm * timeinfo;
      char buffertime[80];


    int clientAddrSize = sizeof(clientAddr);

    string line;
    DWORD WINAPI receive_cmds(LPVOID lpParam)
    {

    printf("thread created\r\n");

    SOCKET current_client = (SOCKET)lpParam;

    boolean contando = false;


    int sensor ;
    double tempo;
    string estado;
    boolean houve_leitura;

    while (recv(current_client, buffer, sizeof(buffer), 0) )
        {

        //closesocket(current_client);
        houve_leitura = false;
        if(buffer[0] == '3')
        {
           string organized[50];

            int i = 0;
            std::string s = buffer;
            std::string delimiter = " ";

            size_t pos = 0;
            std::string token;
            while ((pos = s.find(delimiter)) != std::string::npos)
            {
                token = s.substr(0, pos);
                organized[i] = token;
                i++;
                s.erase(0, pos + delimiter.length());           // preenche a organized
            }


            cout << "STARTED SAMPLE GATHERING PROTOCOL" << endl << endl;
             cout << "TEST SUBJECT " << organized[1] <<" WITH SENSOR " << organized[2] << endl << endl;


              int aa = 0;
              bool found = false;
              bool found2 = false;
                while(!areasubject[aa][0].empty())
                {
                    if(areasubject[aa][0] == organized[3])
                    {
                        found2 = true;
                        int bb = 1;
                        while(!areasubject[aa][bb].empty())
                        {
                           if(areasubject[aa][bb] == organized[1])
                           {
                               cout << "TEST SUBJECT ALREADY REGISTERED" <<endl;
                               found = true;
                           }
                           bb++;
                        }
                        if(found == false)
                        {
                            areasubject[aa][bb] = organized[1];
                            cout << "NEW TEST SUBJECT REGISTERED TO AREA ";
                            cout << organized[3]<<endl<<endl;
                        }

                    }
                    aa++;
                }
                if(found2 == false)
                {
                    areasubject[aa][0] = organized[3];
                     areasubject[aa][1] = organized[1];
                    cout << "NEW TEST SUBJECT REGISTERED TO NEW AREA ";
                    cout << organized[3]<<endl<<endl;
                }

        }
        if(buffer[0] == '4')
        {
            string organized[50];



            int i = 0;
            std::string s = buffer;
            std::string delimiter = " ";

            size_t pos = 0;
            std::string token;
            while ((pos = s.find(delimiter)) != std::string::npos)
            {
                token = s.substr(0, pos);
                organized[i] = token;
                i++;
                s.erase(0, pos + delimiter.length());           // preenche a organized
            }
            cout << "STOPPED SAMPLE GATHERING PROTOCOL" << endl << endl;
            cout << "TEST SUBJECT " << organized[1] <<" WITH SENSOR " << organized[2] << endl << endl;

            ofstream ofp;
             ofp.open("config.txt", ios::out);

            int x = 0;
            while(!areasubject[x][0].empty())
            {
                int y = 0;
                 while(!areasubject[x][y].empty())
                 {
                     ofp<<areasubject[x][y];
                     ofp<<" ";
                     y++;
                 }
                 ofp<<"\n";
                 x++;
            }
            closesocket(current_client);
            ofp.close();

            }

        if(buffer[0] == '1')
        {
            //logfile << buffer << endl; // a trama toda que e recebida
            //cout << buffer << endl;

            string organized[50];

            int i = 0;
            std::string s = buffer;
            std::string delimiter = " ";

            size_t pos = 0;
            std::string token;
            while ((pos = s.find(delimiter)) != std::string::npos)
            {
                token = s.substr(0, pos);
                organized[i] = token;
                i++;
                s.erase(0, pos + delimiter.length());           // preenche a organized
            }

        int samplenr = std::stoi(organized[5]);


        sensor = std::stoi(organized[1]);
        tempo = std::stod(organized[2]);

        int l = 0;
        double sumAcX = 0;
        double sumAcY = 0;
        double sumAcZ = 0;
        while(l<samplenr)
        {

            sumAcX += std::stod(organized[6+(7*l)]);
            sumAcY += std::stod(organized[7+(7*l)]);
            sumAcZ += std::stod(organized[8+(7*l)]);
            l++;
        }

        thisAcX = sumAcX / samplenr;
        thisAcY = sumAcY / samplenr;
        thisAcZ = sumAcZ / samplenr;

        double result = sqrt(pow(thisAcX,2) + pow(thisAcY,2) + pow(thisAcZ,2));
        cout << result << endl;

        std::clock_t start;
        double duration;

        if((result > 0.93 && result < 1.075)|| (result > -1.075 && result < -0.93 ))
        {
            if (contando == false )
            {
                contando = true;
                start = std::clock();
            }

            cout << "Parado" <<endl;
            estado = "Parado";

            duration = ( std::clock() - start ) / (double) CLOCKS_PER_SEC;
            if (duration > 15)
            {
                 cout << "ALARME : INATIVO" <<endl<<endl;
                 estado += "  ALARME : INATIVO";
            }
             cout << endl;

        }
        else
        {
                contando = false;

                cout << "Em Movimento" <<endl;
                estado =  "Em Movimento";

             if (thisAcZ > 1.1)
             {
                 cout << "ALARME : A CAIR"<<endl;
                estado += "  ALARME : A CAIR";
             }
             if (result > 1.5)
             {
                 cout << "ALARME : AGITADO" <<endl;
                 estado += "  ALARME : AGITADO";
             }
             cout << endl;
        }
         houve_leitura = true;
        }

        lastAcX = thisAcX;
        lastAcY = thisAcY;
        lastAcZ = thisAcZ;

        if(buffer[0] == '2')
        {
            errorfile << buffer << endl;
            cout << "ERROR: " << buffer << endl;
        }



        if (houve_leitura == true)
         {


        time (&rawtime);
        timeinfo = localtime(&rawtime);
        strftime(buffer,sizeof(buffer),"%d-%m-%Y %H:%M:%S",timeinfo);
        std::string tempostr(buffer);
        std::cout << tempostr;
        logfile << sensor << " " << tempostr << " " << estado << endl;


        MYSQL* conn;
        conn = mysql_init(0);
        conn = mysql_real_connect(conn,"192.168.43.67","admin","admin","hospital",0,NULL,0);
        if(!conn)
        {cout<<"ERRO Na conexão com o servidor"<<endl<<endl;}
        int qstate = 0;

        string Sensor = std::to_string(sensor);
        string Tempo = tempostr;
        string Estado = estado;

        string s = "INSERT INTO temp2(SENSOR,DATA,ESTADO) VALUES('"  + Sensor +"','" + Tempo +"','" + Estado + "')";

            const char* q = s.c_str();
        qstate = mysql_query(conn, q);
        if(qstate == 0)
        {
            cout <<" Enviada com sucesso para o servidor" <<endl<<endl;
        }
        else
        {
            cout <<" Erro no envio para o servidor" <<endl<<endl;
        }

        }
        memset(buffer, 0, sizeof(buffer));

        }

         ExitThread(0);
}




    void Arranque()
{
        ifstream f("config.txt"); //taking file as inputstream
        string str;
        if(f) {
                ostringstream ss;
            ss << f.rdbuf(); // reading data
            str = ss.str();
        }
            string lines[20];
            int i = 0;
            std::string s = str;
            std::string delimiter = "\n";
            size_t pos = 0;
            std::string token;
            while ((pos = s.find(delimiter)) != std::string::npos)
            {
                token = s.substr(0, pos);
                lines[i] = token;
                lines[i] += " ";
                i++;
                s.erase(0, pos + delimiter.length());          // preenche o array lines com uma linha do ficheiro em cada posiçao


            }

            int a = 0;
            while(a<6)
            {
                string words[20];
                int i2 = 0;
                int sub = 0;
                std::string s2 = lines[a];
                std::string delimiter2 = " ";
                size_t pos2 = 0;
                std::string token2;
                while ((pos2 = s2.find(delimiter2)) != std::string::npos)
                {
                    token2 = s2.substr(0, pos2);
                    words[i2] = token2;
                    i2++;
                    s2.erase(0, pos2 + delimiter2.length());           // preenche o array words com uma palavra em cada posiçao
                }
                while(sub<6)
                {
                    areasubject[a][sub] = words[sub];
                    sub++;
                }
                a++;
            }

    int aa = 0;
    while(!areasubject[aa][0].empty())
    {
		//cout<<"MANAGING AREA ";
		//cout<<areasubject[aa][0];
		//cout<<" WITH TEST SUBJECTS ";
		int bb = 1;
		while(!areasubject[aa][bb].empty())
        {
          // cout<<areasubject[aa][bb];
          // cout<<" ";
           bb++;
        }
        cout<< endl;

		aa++;
    }
        cout<< endl<<endl;
}







int main()
{



    HANDLE hStdout = GetStdHandle(STD_OUTPUT_HANDLE);
    SetConsoleTextAttribute(hStdout, FOREGROUND_GREEN|FOREGROUND_INTENSITY);


    std::thread tArranque(Arranque);

  	tArranque.join();


    //WSAStartup(MAKEWORD(2,0), &WSAData);
    int ret = WSAStartup(0x101,&WSAData);
    if(ret != 0)
    {
        return 0;
    }


    server = socket(AF_INET, SOCK_STREAM, 0);

     if(server == INVALID_SOCKET)
    {
    return 0;
    }

    serverAddr.sin_addr.s_addr = INADDR_ANY;
    serverAddr.sin_family = AF_INET;
    serverAddr.sin_port = htons(7778);

    DWORD thread;



    bind(server, (SOCKADDR *)&serverAddr, sizeof(serverAddr));


    //listen(server, 0);
     if(listen(server,5) != 0)
 {
    return 0;
 }

    cout << "STANDBY" << endl;


    while(true)
    {
        client = accept(server, (SOCKADDR *)&clientAddr, &clientAddrSize);



         CreateThread(NULL, 0,receive_cmds,(LPVOID)client, 0, &thread);
        //std::thread tComunication(Comunication);

        //tComunication.join();


    }
    closesocket(client);
    WSACleanup();

return 0;
}
