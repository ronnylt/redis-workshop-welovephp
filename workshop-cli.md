# Keys and values

The essence of a key-value store is the ability to store some data,
called "the value", inside a "key". This data can later be retrieved only if we
know the exact key used to store it. 

We can use the command SET to store the value:

	set event:name WeLovePHP
	get event:name

Redis will store our data permanently, so we can later ask "What is the value
stored at the given key?".

Other common operations provided by key-value stores are DEL to delete a given
key and associated value, 

SET-if-not-exists (called SETNX on Redis) that sets a key only if it does not 
already exist.

# Atomic counters

INCR to atomically increment a number stored at a given key:

	set attendees 0
	incr attendees
	incr attendees
	incr attendees 5
	incrby attendees 5
	del attendees
	incr attendees

There is something special about INCR. 
Why do we provide such an operation if we can do it ourself with a bit of code? 
After all it is as simple as:

    x = GET count
    x = x + 1
    SET count x

The problem is that doing the increment in this way will only work as long as
there is a single client using the key. See what happens if two clients are
accessing this key at the same time:

1. Client A reads *count* as 10.
2. Client B reads *count* as 10.
3. Client A increments 10 and sets *count* to 11.
4. Client B increments 10 and sets *count* to 11.

We wanted the value to be 12, but instead it is 11! This is because
incrementing the value in this way is not an atomic operation.  Calling the
INCR command in Redis will prevent this from happening, because it *is* an
atomic operation. Redis provides many of these atomic operations on different
types of data.

# Keys expiration

Redis can be told that a key should only exist for a certain length of time.
This is accomplished with the EXPIRE and TTL commands:

	expire event:locked 1
	expire event:locked 10800
	ttl event:locked

This causes the key event:locked to be deleted in 10800 seconds. You can test
how long a key will exist for with the TTL command. It returns the number of
seconds until it will be deleted:

	ttl event:locked
	ttl event:count

The *-1* for the TTL of the key *count* means that it will never expire. Note
that if you SET a key, its TTL will reset.

# Lists
Redis also supports several more complex data structures. The first one we'll
look at is a list.  

A list is a **series of ordered values**.

Some of the important commands for interacting with lists are RPUSH, LPUSH, LLEN,
LRANGE, LPOP, and RPOP.  You can immediately begin working with a key as
a list, as long as it doesn't already exist as a different type.

RPUSH puts the new value at the end of the list:

	rpush users adan
	rpush users marcos
	rpush users javier
	rpush users manu
	lpsuh users gonzalo


LPUSH puts the new value at the start of the list.

LRANGE gives a subset of the list. It takes the index of the first element
you want to retrieve as its first parameter and the index of the last element
you want to retrieve as its second parameter. A value of -1 for the second
parameter means to retrieve all elements in the list:

	lrange users 0 -1
	1) "adan"
	2) "marcos"
	3) "javier"
	4) "manu"
	5) "gonzalo"

	lrange users 0 1
	1) "adan"
	2) "marcos"

	lrange users 1 2
	1) "marcos"
	2) "javier"

	lrange users 2 -1
	1) "javier"
	2) "manu"
	3) "gonzalo"


LLEN returns the current length of the list:

	llen users
	(integer) 5


LPOP removes the first element from the list and returns it:

	lpop users
	"adan"

RPOP removes the last element from the list and returns it:

	rpop users
	"gonzalo"

Check the length of the list now:

	llen users
	(integer) 3

# Sets

The next data structure that we'll look at is a set. A set is similar to a
list, except it *does not have a specific order* and each element may only appear
once. Some of the important commands in working with sets are SADD, SREM,
SISMEMBER, SMEMBERS and SUNION.

SADD adds the given value to the set:

	sadd superpowers vim
	sadd superpowers awk
	sadd superpowers top
	sadd superpowers emacs

SREM removes the given value from the set:

	srem superpowers top

SISMEMBER tests if the given value is in the set:


	sismember superpowers ls
	(integer) 0

	sismember superpowers vim
	(integer) 1


SMEMBERS returns a list of all the members of this set:

	smembers superpowers
	1) "vim"
	2) "awk"
	3) "emacs"

SUNION combines two or more sets and returns the list of all elements:

	sadd commands ls
	sadd commands ps
	sadd commands top
	sadd commands vim
	sadd commands du
	sadd commands pwd
	sadd commands awk


	SUNION commands superpowers
	1) "emacs"
	2) "ps"
	3) "pwd"
	4) "ls"
	5) "vim"
	6) "awk"
	7) "du"
	8) "top"

SINTER Returns the members of the set resulting from the intersection of all 
the given sets:

	sinter commands superpowers
	1) "vim"
	2) "awk"

# Sorted sets

The last data structure which Redis supports is the sorted set.  It is similar
to a regular set, but now each value has an associated score.  This score is
used to *sort* the elements in the set:

	ZADD hackers 1940 "Alan Kay"
	ZADD hackers 1953 "Richard Stallman"
	ZADD hackers 1965 "Yukihiro Matsumoto"
	ZADD hackers 1916 "Claude Shannon"
	ZADD hackers 1969 "Linus Torvalds"
	ZADD hackers 1912 "Alan Turing"

In these examples, the scores are years of birth and the values are the names
of famous hackers:

	ZRANGE hackers 2 4
	1) "Alan Kay"
	2) "Richard Stallman"
	3) "Yukihiro Matsumoto"

Sorted sets scores can be updated at any time. Just calling again ZADD against
 an element already included in the sorted set will update its score (and position) 
 in O(log(N)), so sorted sets are suitable even when there are tons of updates.


# Hashes

Redis Hashes are maps between string fields and string values, so they are the
perfect data type to represent objects (eg: A User with a number of fields like
name, surname, age, and so forth):

For example, for the above event example, we should have:

	hset event name WeLovePHP
	hset event date 2013-04-13
	hset event duration 3
	hset event venue Softonic

	hgetall event
	1) "name"
	2) "WeLovePHP"
	3) "date"
	4) "2013-04-13"
	5) "duration"
	6) "3"
	7) "venue"
	8) "Softonic"

	hget event name 
	"WeLovePHP"

A hash with a few fields (where few means up to one hundred or so) is stored in 
a way that takes very little space, so you can store millions of objects in a 
small Redis instance.

While Hashes are used mainly to represent objects, they are capable of storing 
many elements, so you can use Hashes for many other tasks as well.

Every hash can store up to 2^32-1 field-value pairs (more than 4 billion).

