#include <iostream>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "serial.h"
using namespace std;
#include <winsock2.h>
#include "winsock2.h"
#include <windows.h>
#include<string>
#include <time.h>
#include <fstream>
#include <thread>         // std::thread
#include <sys/time.h>
#include <sstream>
#include <iostream>
#include <string>
#include <fstream>
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



char output[255];
char incomingData[MAX_DATA_LENGTH];
int SIZEE=2;

string Sujeito = "Matos";
string ISStemp = "1";
string Area = "1";

struct listaStruct{
	string iss;
	string nome;
}listaNomes[2];

char Niss;
string sujeitoMandar;

using std::cout;
using std::endl;

int i=0;


char *port_name = "\\\\.\\COM11";
SerialPort arduino(port_name);

bool stoporder = false;
bool readytostop = false;

string line;
void Arranque()
{
	ifstream myfile ("logvalues.txt");
	if (myfile.is_open())
  	{
		getline (myfile,line);
	}
}


void Gestao()
{
    WSADATA WSAData;
    SOCKET server;
    SOCKADDR_IN addr;

    WSAStartup(MAKEWORD(2,0), &WSAData);
    server = socket(AF_INET, SOCK_STREAM, 0);

    addr.sin_addr.s_addr = inet_addr("127.0.0.1");
    addr.sin_family = AF_INET;
    addr.sin_port = htons(7778);

    connect(server, (SOCKADDR *)&addr, sizeof(addr));
    cout << "Connected to server!" << endl;

    char start[20];


    string start2 ="3 " ;
	start2 +=Sujeito;
	start2 += " ";
	start2 += ISStemp;
	start2 += " ";
	start2 += Area;
	start2 += " ";

	strcpy(start, start2.c_str());

    send(server, start,sizeof(start) , 0); // envia a mensagem de start para o gestor

	while(arduino.isConnected() && stoporder == false)
	{

	    int done = 1;
		int z = 0;
		Sleep(0);
		char test[1];

		arduino.readSerialPort(&test[0], 1);

		while(test[0]!='~')
		{
			arduino.readSerialPort(&test[0], 1);
		}

		 memset(test,0,1);

		 ofstream logfile ("log.txt",fstream::app);
		 ofstream errorfile ("error.txt",fstream::app);


		 while(done > 0 )
		 {
		 	arduino.readSerialPort(&output[z], 1);


		 	if(output[z] == '\n')
		 	{

				string organized[50];

				int i = 0;
				std::string s = output;
				std::string delimiter = " ";

				size_t pos = 0;
				std::string token;
				while ((pos = s.find(delimiter)) != std::string::npos)
				{
 				    token = s.substr(0, pos);
 				    organized[i] = token;
 				    i++;
 				    s.erase(0, pos + delimiter.length());
				}



			 	if (output[0] == '1')
		 		{

						cout<< "\n";

					    int samplenr = std::stoi(organized[5]);

						int l = 0;
						send(server, output, sizeof(output), 0);
						memset(output,0,30);
						cout << "Enviado" << endl;
						logfile << "\n";
			  	}

				if (output[0] == '2')
		 		{
		 		    if(output[8] == '4')
                    {
                     stoporder = true;
                     readytostop = true;
                    }
					errorfile << output;
					cout<<"ERRO"<<endl;
					cout<<output<<endl;

					send(server, output, sizeof(output), 0);

			 	}

				 logfile.close();
			 	 errorfile.close();
				 done--;

				 memset(output, 0, 255);
			}
			z++;
		}
	}
	char stop[20];

	string stop2 ="4 " ;
	stop2 +=Sujeito;
	stop2 += " ";
	stop2 += ISStemp;
	stop2 += " ";
	stop2 += Area;
	stop2 += " ";


	strcpy(stop, stop2.c_str());

	send(server, stop,sizeof(stop) , 0); // manda stop para o gestor
	readytostop = true;
	    						closesocket(server);
    						WSACleanup();
}

void Comunicacao()
{
	cout<<"STOP: "<<endl;
	string Stop;
	cin>>Stop;

	char strstop[2];
	strcpy(strstop,"4\n");
	stoporder = true;

	while(readytostop = false)
	{
		Sleep(0);
					 }

	arduino.writeSerialPort(strstop, sizeof(strstop));

}

    WSADATA WSAData;
    SOCKET server, client;
    SOCKADDR_IN serverAddr, clientAddr;
    char buffer[1024];
    int clientAddrSize = sizeof(clientAddr);

    DWORD WINAPI receive_cmds(LPVOID lpParam)
    {

        printf("thread created\r\n");
        SOCKET current_client = (SOCKET)lpParam;


        WSADATA WSADatages;
        SOCKET serverges;
        SOCKADDR_IN ges;

        WSAStartup(MAKEWORD(2,0), &WSAData);
        serverges = socket(AF_INET, SOCK_STREAM, 0);

        ges.sin_addr.s_addr = inet_addr("127.0.0.1");
        ges.sin_family = AF_INET;
        ges.sin_port = htons(7778);

        connect(serverges, (SOCKADDR *)&ges, sizeof(ges));
        cout << "Connected to gestor!" << endl;

        system("pause"); //espera por um enter//
		cout<<"START: "<<endl;
        send(current_client, "START", sizeof("START"), 0);

		std::thread tComunicacao(Comunicacao);

        while (recv(current_client, buffer, sizeof(buffer), 0) && stoporder == false)
            {
            cout << buffer << endl;
            send(serverges, buffer, sizeof(buffer), 0);
            Sleep(1000);
            }
            send(serverges, "4", sizeof("4"),0);
            system("taskkill /F /T /IM Simulado.exe");
            std::abort();

    ExitThread(0);

    }

 int simul(){


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
    serverAddr.sin_port = htons(7777);

    DWORD thread;
    bind(server, (SOCKADDR *)&serverAddr, sizeof(serverAddr));
     if(listen(server,5) != 0)
 {
    return 0;
 }

    cout << "STANDBY" << endl;

    while(true)
    {
        client = accept(server, (SOCKADDR *)&clientAddr, &clientAddrSize);
        CreateThread(NULL, 0,receive_cmds,(LPVOID)client, 0, &thread);
    }
    closesocket(client);
    WSACleanup();

return 0;
}




    int main(){


    HANDLE hStdout = GetStdHandle(STD_OUTPUT_HANDLE);
    SetConsoleTextAttribute(hStdout, FOREGROUND_BLUE|FOREGROUND_INTENSITY);

	std::thread tArranque(Arranque);
  	tArranque.join();

	if(arduino.isConnected()){
		cout<<"Arduino conectado corretamente"<<endl<<endl;
	}
	else{

        simul();

		cout<<"Erro na porta"<<endl<<endl;
			    for (int i=0;i< 2;i++) {
        cout << listaNomes[i].iss<<"  ";
        cout << listaNomes[i].nome<<" ";
}
	}



	while(arduino.isConnected()){


		char str[20];
		string valoresRAW;
		cout<<"Deseja introduzir valores diferentes? (Y/N)"<<endl<<endl;
		string yes;
		cin>>yes;

		if (yes==("Y") || yes==("y"))
		{

			cout<<"Numero de amostras: "<<endl;
			string data;
			cin>>data;

			cout<<"Tempo entre leituras: "<<endl;
			string tempoDelay;
			cin>>tempoDelay;

			cout<<"Tempo da amostra completa: "<<endl;
			string tempoPausa;
			cin>>tempoPausa;


			time_t seconds = time(0);
			std::string number;
			std::stringstream strstream;
			strstream << seconds;
			strstream >> number;

			strcpy(str, data.c_str());
			strcat(str, " ");
			strcat(str,tempoDelay.c_str());
			strcat(str, " ");
			strcat(str,tempoPausa.c_str());
			strcat(str, " ");
			strcat(str,number.c_str());
			strcat(str, "\n");
			cout<<str;
		}
		else
		{
			time_t seconds2 = time(0);
			std::string number2;
			std::stringstream strstream;
			strstream << seconds2;
			strstream >> number2;
			strcpy(str, line.c_str());
			strcat(str, " ");
			strcat(str,number2.c_str());
			strcat(str, "\n");
		}


		system("pause"); //espera por um enter//
		cout<<"START: "<<endl;

        char str3[30];
        strcpy(str3,"3 ");
        strcat(str3, str);
        arduino.writeSerialPort(str3, sizeof(str3));


		std::thread tGestao(Gestao);
		std::thread tComunicacao(Comunicacao);

		tComunicacao.join();
		tGestao.join();
		stoporder = false;
		readytostop = false;


	}
	return 0;
}



