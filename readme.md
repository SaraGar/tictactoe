##################################################
		TRES EN RAYA
##################################################

Proyecto realizado en Symfony 4.4 y PHP 7.3.1. 
Versión web del juego tres en raya. 

La primera pantalla del juego ofrece la posibilidad de introducir dos usuarios (sin necesidad de crear una contraseña, únicamente se requiere el nombre de usuario). Ambos valores son obligatorios y deben ser diferentes. 
Una vez indicados los nombres de usuario, se intenta recuperar la última partida, en caso de que estuviera pendiente de finalizar. De no haberla o no tener interés en recuperarla, se iniciará una nueva.

Los usuarios van colocando una casilla por turno, hasta que alguno de los dos consigue colocar tres fichas que formen una línea vertical, horizontal o diagonal.


Para poner en marcha el programa será necesario contar con un servidor web con PHP, y un gestor de BD MySql.

Se deberá crear en el directorio raíz del proyecto un fichero llamado ".env.local" en el que se configure la conexión a la base de datos, un ejemplo de su contenido sería este: 
DATABASE_URL=mysql://root:password@127.0.0.1:3306/tic-tac-toe?serverVersion=5.7