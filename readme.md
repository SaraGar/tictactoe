# TRES EN RAYA

Proyecto realizado en Symfony 4.4 y PHP 7.3.1. 
Versión web del juego tres en raya. 

## Uso

La primera pantalla del juego ofrece la posibilidad de introducir dos usuarios. Ambos valores son obligatorios y deben ser diferentes. 
Una vez indicados los nombres de usuario, se intenta recuperar la última partida, en caso de que estuviera pendiente de finalizar. De no haberla o no tener interés en recuperarla, se iniciará una nueva.

![Home preview](https://lh6.googleusercontent.com/KqJHoaZr0084xlqdkrXJQ1-1wrGzvuHCRQAeHFkh9GbX-ZymmSXKlpmQLjfWqAOprMNCiiXMLJyNZb76ncGx=w1296-h630)

Los jugadores pueden ser dos personas que jueguen en el mismo ordenador, o una sola persona que juegue contra un oponente automático.

Los usuarios van colocando una casilla por turno, hasta que alguno de los dos consigue colocar tres fichas que formen una línea vertical, horizontal o diagonal.

![Game preview](https://lh4.googleusercontent.com/sC3kbq78ef9s22O6z5-4DcEI4hX2fGKFvyGJWVY6WWxprms5kpNwydpKeyLDttpFXINjz8ZNkNhKpd_AtEoH=w1296-h630)

## Build

Para poner en marcha el programa será necesario contar con un servidor web con PHP, y un gestor de BD MySQL.

Se deberá crear en el directorio raíz del proyecto un fichero llamado `.env.local` en el que se configure la conexión a la base de datos, su contenido sería este: 

```sh
 Estructura:
 DATABASE_URL=mysql://USER:PASSWORD@HOST:PORT/DATABASE_NAME?serverVersion=x.y
 
 Ejemplo:
 DATABASE_URL=mysql://root:password@127.0.0.1:3306/tic-tac-toe?serverVersion=5.7  
```
