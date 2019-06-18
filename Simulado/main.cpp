#include "winsock2.h"
#include<string>
#include <time.h>     // std::thread
#include <sys/time.h>
#include <windows.h>
#include <winsock.h>
#include <stdio.h>
#include <iostream>
#include <conio.h>
#include <signal.h>
#include <stdio.h>
#include <iostream>
#include <stdio.h>
#include <unistd.h>
#include <stdlib.h>
#include <string.h>
using namespace std;
#include<string>
#include<sys/types.h>
#include <fstream>
#include <winsock2.h>
#include <math.h>
#include<sstream>
#include <thread>         // std::thread
#include <cstdio>
#include <ctime>


char output[255];

char Sujeito = '6';

using std::cout;
using std::endl;

int i=0;

    WSADATA WSAData;
    SOCKET server;
    SOCKADDR_IN addr;



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

void s_handle(int s)
{
    if(server)
       closesocket(server);
    WSACleanup();
    Sleep(2000);
    cout<<"EXIT SIGNAL :"<<s;
    exit(0);
}

void s_cl(char *a, int x)
{
    cout<<a;
    s_handle(x+1000);
}

void Comunicacao()
{
    char stop [2];

    if ( readytostop == true)
    {
        while(recv(server, stop, sizeof(stop), 0))
        {
            cout<<stop<<endl;
            if(stop[0] == 'P')
            {   stoporder = true;
                cout<<"STOP ORDER RECEIVED "<<endl;
                ExitThread(0);
            }
        }
    }
}



int main(){

	std::thread tArranque(Arranque);
  	tArranque.join();

    int res,i=1,port=999;

    res = WSAStartup(MAKEWORD(1,1),&WSAData);      //Start Winsock

        if(res != 0)
        s_cl("WSAStarup failed",WSAGetLastError());

     server=socket(AF_INET,SOCK_STREAM,IPPROTO_TCP);       //Create the socket
        if(server==INVALID_SOCKET )
            s_cl("Invalid Socket ",WSAGetLastError());
        else if(server==SOCKET_ERROR)
            s_cl("Socket Error)",WSAGetLastError());
        else
            cout<<"Socket Established"<<endl;



    addr.sin_addr.s_addr = inet_addr("127.0.0.1");
    addr.sin_family = AF_INET;
    addr.sin_port = htons(7777);

    connect(server, (SOCKADDR *)&addr, sizeof(addr));
    cout << "Connected to concentrador!" << endl;

    while(true)
    {
    recv(server, output, sizeof(output), 0);
    cout<<output<<endl;
    Sleep(100);

    if(output[0] == 'S')
    {
        printf("aqui");
        memset(output,0,3);
        std::thread tComunicacao(Comunicacao);
    stoporder = false;
	while(stoporder == false)
	{
	    int done = 1;
		int z = 0;
		Sleep(0);
    int a=rand()%2;
    string s;
    if(a == 1){
    s = "1 6 3 200 200 2 0.0 1 1 0 0 0 20 0.5 2 -1 10 10 10 45 ";
    }
    else{
    s = "1 6 3 200 200 2 0 0 0 0 0 0 20 0 0 0 10 10 10 45 ";
    }


    cout<<a<<endl;

    int n = s.length();

    strcpy(output, s.c_str());
        readytostop = false;
        send(server, output, sizeof(output), 0);
        readytostop = true;
        memset(output,0,30);
        Sleep(1000);
        cout << "Enviado" << endl;
    }
	}
    }



    closesocket(server);
    WSACleanup();


    }

