# PRAF 1.8 (Beta)
Part, Replace And Fulfill Chiper System

==============================
HOW TO INSTALL IN LOCAL SERVER
==============================

 - Download PRAF.php, data.php, database.php and sql.txt
 - Enter to your local database administrator and run sql.txt
 - Copy all the .php files ON THE SAME FOLDER
 - Now it's all ready to run.

=========
CHANGELOG
=========

- 08/12/2015, 00:22 | PRAF is born, version 1.0 is implemented
- 09/12/2015, 10:20 | New isolated DataBase created for PRAF.
- 12/12/2015, 18:36 | New parameters added
- 13/12/2015, 11:41 | Bugfix, memory cannot be 0: PRAF Version 1.1
- 27/12/2015, 12:05 | Improvement of PRAF; now supporting POST petitions (CAUTION: Test still pending): PRAF Version 1.2 (Beta)
- 27/12/2015, 12:21 | New functionality of PRAF; now supporting bin data: PRAF Version 1.3 (Beta)
- 27/12/2015, 12:33 | Some validations were moved; now PRAF is slightly faster: PRAF Version 1.4 (Beta)
- 09/01/2016, 23:09 | Added JSON and  XML output mode, and extra data for the output style: PRAF Version: 1.5 (Beta)
- 10/01/2016, 19:35 | Created new isolated files for database and database credentials: PRAF Version: 1.6 (Beta)
- 29/01/2016, 20:25 | "i" parameter now optional, only numeric values greater than 0 allowed; any other value will be treated as "i=0". PRAF Version 1.7 (Beta)
- 03/02/2016, 20:15 | Obsolete functions removed. Perfomance increased. PRAF Version: 1.8 (Beta)
 
==========
PARAMETERS
==========

You can find a list of valid parameters at http://doscar-sole.netau.net/, and here below:

Here is a list of parameters and all the things it made:

- k: {e: Encode; d: Decode}
	
- m: {n | n % 8 = 0 ∀ n ∈ N: Memory}
	
- i: {∀ n ∈ N: Id; 0: Id}
					
- t: Text
	
- s: Sorting Pattern
	
- p: Filling Pattern
	
- v: {1: Save; !1: Don't Save}
					
- b: {1: Bin data; !1: ASCII data}
					
- o: {JSON: Get the output in JSON format; XML: Get the output in XML format}
					
			
The "v" parameter is only for "k=e", it will save data on database if "v=1", and won't do it otherwise.

If you used "v=1", you can later use "i=n"; if not, you can only use "i=0";
	
	
	
"i" is only for "k=d".
	
"s" is only for "k=d".
	
"p" is only for "k=d".
	
	
	
If "i=0", "s" and "p" are mandatory; if "i=n", "s" and "p" are not needed.
	
	
	
"k" is mandatory.
	
"t" is mandatory.

"m" is optional, 1024 will be used if there is not "m" specified.
	
	
"b" is optional, null will be used if there is not "b" specified.
	
	
"o" is optional, raw data format will be used if there is not "o" specified
	
	
	
If "k=e" and "b=1", Base64 input is needed
	
	
If "b=1" was used on "k=e", is needed to use "b=1" on "k=d", but no other special thing is needed.
	
	
	
So, a example URL can be "http://-------.com/PRAF/PRAF.php?k=e&t=hello&m=8&v=0"


=========
DISCLAMER
=========

This chiper system has been not fully tested yet. IT CAN'T BE INSECURE, so don't use it but for research porpouse.
