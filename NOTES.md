Book a lesson

- Add record to appointments table
- Add record to users_appointments table

Book a bundle

- Add X number of records to the appointments table
	- Each record should be associated with a staff member
	- The first record should have a date, start time and end time
	- Any records have the first should have a blank date, start time and end time
- Add X number of records to the users_appointments table

Book a school/clinic

- Add record to the appointments table
- Add X number of records to the users_appointments table
	- X = the number of people attending the school

Book a program/event/team

- Create the program/event/team
- Creating the program/event/team will automatically add records to the appointments table
- Each person that joins the program/event/team adds a single record to the users_appointments table
	- User limit set on the service

Creating an iCalendar file

- https://gist.github.com/jakebellacera/635416
- https://github.com/eluceo/iCal
- https://github.com/fruux/sabre-vobject