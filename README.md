# local_normalize_grades
Pre-calculate Moodle grades and keep them updated for direct DB grade access.

# Functionality
Will store student-accurate course total grade values for easy DB retrevial for integration with Student inforamtion systems.

# Caveats
As configured by default, this local plugin will store the course total grades AS STUDENTS SEE THEM.
If you wish for grades to be stored as faculty see them, let me know and I'll add the functionality.

#What does "AS STUDENTS SEE THEM" mean?
This system adheres to the "Hide totals if they contain hidden items" report value of the specified report.
Example: "Show totals including hidden items" will give a total matching what the instructor sees as hidden items are calculated the same for both teacher and student.
Whereas "Show totals excluding hidden items" will give one total for students (with hidden items excluded) and one total for teachers (because they can view hidden items).

#Wait? Moodle does this?
Yes. Yes it does and has been a problem for anyone who modifies the "Hide totals if they contain hidden items" value to exclude or hide items.

#What if reports are set to different values?
This system will recognize that and display a warning to the teacher in any course where all grade reports have mismatched "Hide totals if they contain hidden items" values.

# When will this run?
The scheduled task will run every hour, but it's comepletely configuable.
It will not run on a course / user (after the 1st time) if the course total stored in the Moodle DB has not changed OR the instructor has not changed the "Hide totals if they contain hidden items" value for the selected report.

# How do I install this plugin?
Clone the repository into your Moodle/local folder as "normalize_grades" and go to your site home.
