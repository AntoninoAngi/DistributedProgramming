#include     <stdlib.h>
#include     <string.h>
#include     <inttypes.h>
#include     "../errlib.h"
#include     "../sockwrap.h"
#include <sys/stat.h>
#include <sys/types.h>
#include <unistd.h>
#include <fcntl.h> // for open

#define BUFLEN 8192  /* BUFFER LENGTH */
#define TIMEOUT 15  /* TIMEOUT (seconds) */

/* FUNCTION PROTOTYPES */
char *prog_name;

int main (int argc, char *argv[])
{
	char     buf[BUFLEN];		/* transmission buffer */
    char	 rbuf[BUFLEN];	/* reception buffer */

    uint16_t tport_n, tport_h;	/* server port number (net/host ord) */

    int		   s;
    fd_set cset;
    struct timeval tval;
    int		   result;
    struct sockaddr_in	saddr;		/* server address structure */
    struct in_addr	sIPaddr; 	/* server IP addr. structure */
    int i;
    //char string[BUFLEN];
    int num_sec;

    if (argc < 4){
    	fprintf(stderr, "Error on number of parameters\n");
    	exit(1);
    }

    prog_name = argv[0];

    result = inet_aton(argv[1], &sIPaddr);
    if (!result)
		err_quit("Invalid address");

    if (sscanf(argv[2], "%" SCNu16, &tport_h)!=1)
		err_quit("Invalid port number");
    tport_n = htons(tport_h);

    /* create the socket */
    printf("Creating socket\n");
    s = Socket(AF_INET, SOCK_STREAM, IPPROTO_TCP);
    printf("done. Socket fd number: %d\n",s);

    /* prepare address structure */
    bzero(&saddr, sizeof(saddr));
    saddr.sin_family = AF_INET;
    saddr.sin_port   = tport_n;
    saddr.sin_addr   = sIPaddr;

    /* connect */
    showAddr("Connecting to target address", &saddr);
    Connect(s, (struct sockaddr *) &saddr, sizeof(saddr));
    printf("done.\n");

    uint32_t filesize;
    int j;

	 for (i = 3; i < argc; i++){
	    size_t		len, n;
	    sprintf(buf, "GET %s\r\n", argv[i]);
	    len = strlen(buf);
	    Writen(s, buf, len);

	    printf("waiting for response...\n");
	    FD_ZERO(&cset);
	    FD_SET(s, &cset);
	    tval.tv_sec = TIMEOUT;
	    tval.tv_usec = 0;
	    n = Select(FD_SETSIZE, &cset, NULL, NULL, &tval);

	    if (n > 0){
	    	Readn (s, rbuf, 3);
	    	if (strcmp (rbuf, "-ER") == 0){
	    		fprintf(stderr, "Not existing file\n");
	    		Readn (s, &rbuf[3], 3);
	    	}else{
	    		Readn (s, &rbuf[3], 2);
	    		mode_t mode = S_IRWXU | S_IRWXG;
	    		int fp = open (argv[i], O_APPEND | O_CREAT | O_WRONLY, mode);
	    		if (fp < 0){
			    	fprintf(stderr, "Error on creating file %s for client\n", argv[i]);
			    	//close(s);
	  			}else{
					n = Readn(s, (void *) &filesize, sizeof(uint32_t)); //so that it receives the byte dimension of the file
					int t = 0;						
					for(j = 0; j < (int) ntohl(filesize) - BUFLEN - 1; j += BUFLEN){
						n = Readn(s, rbuf, j);
						t += n;
						Write(fp, rbuf, strlen(rbuf));
					}
					int lett = ((int) ntohl(filesize))%BUFLEN;
					n = Readn(s, rbuf, lett);
					t += n; 
					if (n > 0){
						rbuf[t] = '\0';
						write(fp, rbuf, strlen(rbuf));
					}
					Readn(s, (void *) &num_sec, sizeof(uint32_t));
					fprintf(stdout, "File transferred %s %d %d\n", argv[i], (int) ntohl(filesize), num_sec);
				}
			}
		}else 
	    	printf("No response received after %d seconds\n", TIMEOUT);
	    printf("=======================================================\n");
	}
	close(s);
	return 0;
}
