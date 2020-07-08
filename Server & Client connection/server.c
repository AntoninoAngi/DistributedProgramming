
#include    <stdlib.h>
#include    <string.h>
#include    <inttypes.h>
#include 	<string.h>
#include 	<fcntl.h> // for open
#include 	<unistd.h> // for close
#include    "../errlib.h"
#include    "../sockwrap.h"
#include <sys/stat.h>
#include <sys/types.h>

#define BUFLEN		8192  /* Buffer length */
#define TIMEOUT		15
#define FILENAME	4096

char *prog_name;
uint32_t FileSize (char *nomefile);

int main (int argc, char *argv[])
{
	int		conn_request_skt;	/* passive socket */
    uint16_t 	lport_n, lport_h;	/* port used by server (net/host ord.) */
    int	 	s;			/* connected socket */
    socklen_t 	addrlen;
    fd_set cset;
    struct timeval tval;
    size_t n;
    //char error[7] = "-ERR\r\n";
    struct sockaddr_in 	saddr, caddr;	/* server and client addresses */ 
    char buf[BUFLEN];
    int		bklog = 1024;		/* listen backlog */
    char filename[FILENAME];
    uint32_t filesize;
	
	prog_name = argv[0];

	if (argc != 2){
		printf("Usage: %s <port number>\n", prog_name);
		exit(1);
	}

	/* get server port number */
    if (sscanf(argv[1], "%" SCNu16, &lport_h)!=1)
		err_sys("Invalid port number");
    lport_n = htons(atoi(argv[1]));

    /* create the socket */
    printf("creating socket...\n");
    s = Socket(AF_INET, SOCK_STREAM, IPPROTO_TCP);
    printf("done, socket number %u\n",s);

    /* bind the socket to any local IP address */
    bzero(&saddr, sizeof(saddr));
    saddr.sin_family      = AF_INET;
    saddr.sin_port        = lport_n;
    saddr.sin_addr.s_addr = htonl(INADDR_ANY);
    showAddr("Binding to address", &saddr);
    Bind(s, (struct sockaddr *) &saddr, sizeof(saddr));
    printf("done.\n");

	/* listen */
    printf ("Listening at socket %d with backlog = %d \n",s,bklog);
    Listen(s, bklog);
    printf("done.\n");

    conn_request_skt = s;

    char a;
    
	while (1){
		addrlen = sizeof(struct sockaddr_in);
		s = Accept(conn_request_skt, (struct sockaddr *) &caddr, &addrlen);
		showAddr("Accepted connection from", &caddr);
		printf("new socket: %u\n",s);
		FD_ZERO(&cset);
	    FD_SET(s, &cset);
	    tval.tv_sec = TIMEOUT;
	    tval.tv_usec = 0;
	    n = Select(FD_SETSIZE, &cset, NULL, NULL, &tval);
	    int m = 0;
	    if (n > 0){
	    	while (a != '\n'){
	    		Readn(s, &a, 1);
	    		fprintf(stderr, "%c", a);
				buf[m]= a;
				m++;
			}
			buf[m] = '\0';
			fprintf(stderr, "%s\n", buf); 
	            if (n != -1){
	            	strcpy(filename, buf + strlen("GET "));
    				filename[strlen(filename) - strlen("\r\n")] = '\0';
					struct stat infos;
					fprintf(stderr, "%s\n", filename);
					int fp = open (filename, O_RDONLY);
					if (fp < 0){ 
						fprintf(stderr, "FILE NON TROVATO\n");
						Writen(s, "-ER", 3);
						Writen(s, "R\r\n", 3);
						close (s);
					}else{
						Writen(s, "+OK", 3);
						Writen(s, "\r\n", 2);
						filesize = htonl(FileSize(filename));
      					Writen(s, (void*)(&filesize), sizeof(uint32_t)); //invio networkbyte
      					//printf("%d\n", filesize);
      					stat(filename, &infos);
					    uint32_t num_sec = infos.st_mtime;
					    while(read(fp, buf, BUFLEN-1) > 0){
					    	//Writen(s, strlen(buf), sizeof(int)); //sapere la dimensione del pezzetto di file
					    	fprintf(stderr, "sto inviando\n");
					    	
					    	//char string[BUFLEN];
					    	

					    	Writen(s, buf, strlen(buf));
					    	//sprintf(string, "%s", buf);
					    	//string_size = strlen(string);
					    	//string[string_size] = '\0';
					    	//Writen(s, (void *) &string_size, sizeof(int));
					    	//Writen(s, string, strlen(string));
  					 	}
  					 	Writen(s, (void*) &num_sec, sizeof(uint32_t));
  					}
		    	} 
	    }
	    else 
	    	printf("No response received after %d seconds\n", TIMEOUT); //
	    printf("=======================================================\n");
	}

	return 0;
}

uint32_t FileSize (char *nomefile){
	struct stat st;
	stat (nomefile, &st);
	return (uint32_t) st.st_size;
}



