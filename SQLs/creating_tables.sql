use 6l;
CREATE TABLE IF NOT EXISTS People
(
  ID int(11) NOT NULL,
  FirstName varchar(100) NOT NULL,
  MiddleName varchar(1) NOT NULL,
  LastName varchar(100) NOT NULL,
  Sex char(1) NOT NULL,
  City varchar(100) NOT NULL,
  State varchar(2) NOT NULL,
  Email varchar(100) NOT NULL,
  PhoneNumber varchar(12) NOT NULL,
  Birthday date NOT NULL,
  Job varchar(100) NOT NULL,
  Company varchar(100) NOT NULL,
  Weight float(11) NOT NULL,
  Height float(11) NOT NULL,
  MailingAddress varchar(100) NOT NULL,
  Postcode varchar(10) NOT NULL,
  Country varchar(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;