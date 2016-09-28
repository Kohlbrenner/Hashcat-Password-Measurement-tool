# A Password Utility in PHP
The idea for this project is to create a password, generation and rating utility in PHP.

This project consists of three utilities:

## Password Rater

The main utility being a password strength meter. This meter uses the minimum cracking speeds found on the hashcat website.  http://hashcat.net/oclhashcat-plus/ and uses those speeds along with a point value system to measure the "strength" of the password according to the time it would take to brute force the hash value of the password. 

The goal of this project is to learn PHP. There now exist better ways of calculating the brute force times. 

## Complex Password Creator
Generates a random password given certain parameters such as length, character set and size.

## Dictionary Password Creator

Generates a random password that is "Human readable" i.e. it inserts dictionary words into the password.


