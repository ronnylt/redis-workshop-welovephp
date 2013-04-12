# Usando Redis como una cache estática

Comencemos con algo sencillo, usar Redis como una cache estática.

__Tarea__: Implementar una cache en Redis (estilo memcached) que permita hacer
sets, gets, check-if-exists, dels, expirations, etc...

__Avanzado__: Tener cuidado con "Thundering herd".

# Mostrando los últimos items agregados (live cache)

Tenemos una aplicación web donde queremos mostrar los últimos 20 items creados
por los usuarios.

Queremos mostrar los últimos N items que han sido agregados.

Usualmente se tiene es una consulta como esta:

	SELECT id, title FROM items ORDER BY ts LIMIT 20

__Tarea__: Crear una "live cache" donde los items vayan siendo agregados a la
cache mientras vayan siendo creados.

__Tarea__: Mostrar los últimos N items agregados.

__Tarea__: Limitar la cantidad de items de la lista.

__Tarea__: Implementar la eliminación de un item.

__Tarea__: Implementar una estructura que permita tener indizados los items por
la primera letra del título.

# Contadores

Queremos mantener un contador de las veces que se ha hecho "click" sobre 
cada item.

Podríamos simplemente agregar una columna más a la tabla de items, pero en casos
de gran concurrencia, esto no es para nada óptimo. Necesitamos algo más rápido
que bloquear-leer-incrementar-escribir.

__Tarea__: Crear las estructuras que permitan contar la cantidad de clicks 
globales, por semana, y por día.

__Tarea__: Mostrar un listado con los items más populares en los últimos 7 días 
y en las últimas 24 horas.


# Favoritos

Tenemos usuarios, queremos que los usuarios puedan mantener un listado de sus
items favoritos.

__Tarea__: Implementar las estructuras necesarias para mantener un listado de
los items favoritos de cada usuario.

__Tarea:__: Mostrar un listado de los items favoritos que tienen en común varios
usuarios.

# Leaderboards (tablas de clasificación)

El ejemplo clásico es la tabla de clasificación de un juego.
Por ejemplo, hagamos un juego a ver cuales son los usuarios que más items han
recomendando.

Tened en cuenta que podemos tener varios miles de recomendaciones por minuto

__Tarea__: Mostrar una tabla de clasificación con los mejores 100 usuarios.
__Tarea__: Mostrar al usuario su rango global actual.

Nota: Estas implementaciones son triviales en Redis, incluso si se tienen
millones de usuarios y millones de nuevas recomendaciones por minuto.

# Items únicos

Otro ejemplo interesante que es relativamente fácil de implementar con Redis, 
pero posiblemente muy difícil con otro tipo de bases de datos, es la problemática
de ver cuántos usuarios únicos visitaron un recurso determinado en una determinada
cantidad de tiempo. 

Por ejemplo, queremos conocer el número de usuarios únicos, que han accedido a 
un determinado item.

__Tarea__: Implementar las estructuras necesarias para llevar la estadística de
cuáles son los usuarios que han (visitado, comprado, recomendado) cierto item en
las últimos (5 mins, 1 hora, 6 horas, 24 horas, 7 días, etc...)

# Lista circular

Su super sistema de predicciones ha determinado cuales son los items que más le
pueden interesar a cierto usuario. Queremos mostar al usuario esos items, de uno
en uno (de tal forma que no sea agobiante para el usuario).

__Tarea__: Implementar estructura que permita mostar un banner rotativo con items
recomendados, un item a la vez.

# Lua scripts

Queremos con un solo comando de redis, obtener un listado de items favoritos de
un usuario, conjuntamente con la cantidad de usuarios que lo han vistidado en los
últimos 5 mins.

__Tarea__: Implementar usando Lua script.


