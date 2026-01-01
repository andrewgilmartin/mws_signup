<?php
#require_once 'event-page-include.php';
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="common.css">
        <link media="screen" rel="stylesheet" type="text/css" href="browser.css">
        <link media="handheld, only screen and (max-device-width: 480px)" href="mobile.css" type="text/css" rel="stylesheet" />
    </head>
<body>
    <div class="page-content">

<h1>Scripting an event</h1>

<p>
This documents how to describe an event using this application's event syntax.
The syntax is quite regular and so what you learn in one area of the syntax will help you understand other areas.
The complete event description is called an <i>event script</i> or, simply, a <i>script</i>.
</p>
<p>
There are two basic clauses in the syntax.
The first is a marker followed by one or more facts.
For example,<pre>name "Jane Jacobs"</pre> has the marker <i>name</i> followed by the <i>Jane Jacobs</i> fact.
If a fact contains spaces, as in this example, then the fact needs to be enclosed in double-quotation marks.
The second clause is a marker followed by a name, then zero, one or more clauses and finally and <i>end</i> marker.
For example, <pre>role Gardener description "A person with dirt under their nails." end</pre> has the marker <i>role</i> followed by the <i>Gardener</i> title of the role, followed by one <i>description</i> clause and finishing with <i>end</i>.
Note that since the role's title, <i>Gardener</i>, does not contain spaces then the title was not quoted. (It is not a mistake to have used <tt>"Gardener"</tt> instead.)
</p>
<p>
Dates must always be entered in the form or <i>YYYY-MM-DD</i>.
For example, June 3, 2011 is entered <tt>2011-06-03</tt>.
Times must always be entered in the form of and hour followed by AM or PM. For example, 7 o'clock is entered <tt>7AM</tt>.
Note that half hours are not yet supported.
</p>

<h2><i>event</i> clause</h2>
<pre>
event "event's title"

    description "event's description"

    contact "contact's name"

    <i>VOLUNTEER PROPERTIES</i>

    <i>DAYS</i>

    <i>ROLES</i>

    <i>ACTIVITIES</i>

    <i>HINTS</i>

end
</pre>
<p>
Use the <i>event</i> clause to specify the event.
The event's title is given immediately after the event term.
The title will be used on all event pages.
This clause contains a several sub-clauses.
Use the <i>description</i> clause to enter the event's description.
The description will appear on all event pages.
The description should be oriented specifically to volunteering and not wholely about the event itself.
The contact should be the name of the primary content for the event.
The contact must already exist in the Signup application and so you must use the <a href="create-contact-page.php">Create Contact</a> to create the contact before creating the event.
</p>
<p>
For information on <i>hint</i>, <i>volunteer property</i>, <i>day</i>, <i>role</i> and <i>activity</i> clauses see below.
These clauses can be used as many times as needed.
</p>

<h2><i>day</i> clause</h2>
<pre>
day "day's title"

    date <i>date</i>

    <i>HOURS</i>

end
</pre>
<p>
Use the <i>day</i> clause to specify each date of the event.
Days include not only the day or days of the event but also the days needed to set up for and clean from the event.
For example, if the event is on Saturday morning and volunteers are needed on Friday night for set up then this event must have two-day clauses.
</p>
<p>
For information on <i>hour</i> clause see below.
The <i>hour</i> clause can be used as many times as needed.
</p>

<h2><i>hour</i> clause</h2>
<pre>
hours "hour's title'"
    starting <i>time</i>
    end <i>time</i>
end
</pre>
<p>
Use the <i>hour</i> clause to specify the times of the public portion of the event.
For example, if Saturday morning's event occurs between 10 AM and noon then you would only specify these hours for Saturday's hours: For example,
<pre>
hours "Morning"
    starting 10AM
    ending 12PM
end
</pre>
</p>

<h2><i>role</i> clause</h2>
<pre>
role "role's name"
    description "role's description"
end
</pre>
<p>
Use the <i>role</i> clause to specify each of kinds of the roles needed from volunteers.
For example, most events need people to volunteer for "setup", "cleanup" and "parking".
These are each specific roles.
Use the <i>description</i> clause to clarify what is expected of the volunteer in this role.
</p>

<h2><i>activity</i> clause</h2>
<pre>
activity "activity's name"

    description "activity's description"

    contact "contact's name"

    <i>DAYS</i>

end
</pre>
<p>
Use the <i>activity</i> clause to specify each of the event's activities.
For example, the Holiday Fair has, among others, the "Bee Room", "School Store", and "Gnome Cave" activities.
Use the <i>description</i> clause to explain to the volunteers what will generally be expected of them for this activity.
Use the <i>contact</i> clause to specify who is the activity's primary contact.
That is, who the volunteer should contact for more information or locate on the day of the event.
</p>
<p>
Use one or more <i>day</i> clauses to specify that day's shifts.
For information on <i>day</i> clause (within an activity) see below.
The <i>day</i>clause can be used as many times as needed.
</p>

<h2><i>day</i> clause</h2>
<pre>
day "day title"
   <i>SHIFTS</i>
end
</pre>
<p>
Use the <i>day</i> clause to group the activity's shifts by day.
For information on <i>shift</i> clause see below.
The <i>shift</i> clause can be used as many times as needed.
</p>
<h2><i>shift</i> clause</h2>
<pre>
shift "role name"
    count <i>number</i>
    starting <i>time</i>
    ending <i>time</i>
end
</pre>
<p>
Use the <i>shift</i> clause to specify each activity shift for which you need a volunteer.
When the volunteer page is shown to the potential volunteer the activity's description, the activity's contact details, and the shift's role's description are all presented.
For example, if an activity needs 2 people for setup, 3 for operation, and 2 for cleanup then 3 <i>shift</i> clauses will be needed.
If setup is from 8 AM to 10 AM then the shifts are specified as
<pre>
shift "Setup"
    count 2
    starting 8AM
    ending 10AM
end
</pre>
When there is only one shift for the given day and role the <i>count</i> clause need not be included.
Of all the clauses, the <i>shift</i> clause will be the most used clause within an event's specification.
</p>

<h1><i>volunteer-property</i> clause</h1>
<pre>
volunteer-property "property's title"
    description <i>description</i>
end
</pre>
<p>
Use the <i>volunteer-property</i> clause to collect other information from the volunteer at signup.
For example, the Holiday Fair asks volunteers to bring a baked good:
To collect this information add the following to your event
<pre>
volunteer-property "Baked Good"
    description "The baked good is sold in the Bakery to raise more funds.
    It is best if the baked good can be divided into individual servings."
end
</pre>
The volunteer's name, email address, and telephone number are always collected.
Currently, name and telephone number are required.
</p>

<h2><i>hint</i> clause</h2>
<pre>
hint <i>name</i> <i>value</i>
</pre>
<p>
Use the <i>hint</i> clause to guide some of the application's decisions. Currently, there is only one hint.
</p>

<p><tt>hide-activities-without-shifts</tt> with values of <tt>true</tt> or <tt>false</tt>.
By default, the application shows all activities for all days.
If you would like to hide activities without shifts then use this hint.
(When developing the event script it is best not to hide empty shifts as the empty activity is likely a reminder of shifts to add.)
</p>

<h2>Example 1</h2>
<p>
This example event script is for a parents &amp; teacher meeting.
The event is happening on a Sunday evening and needs a setup and a cleanup volunteer.
Using the application for such a minimal event might seem excessive but at least volunteering signup is consistent.
Note that there are no contact clauses (contact the 3rd grade teacher), the hours clause has no name (the event's name is enough), and the roles have no descriptions (the names are self-explanatory).
You only need to use the clauses that are required.
In general, the smaller the event the fewer features used.
(However, I would always include an event contact.)
</p>
<pre>
event "Parents & Teacher Night"

    description "This night is a short event to welcome the parents to 3rd grade.
                 It is a low-key meeting but there is setup and cleanup volunteer help needed."

    day "Sunday"
        date 2011-02-20
        hours ""
            starting 6PM
            ending 8PM
        end
    end

    role "Setup"
    end

    role "Cleanup"
    end

    activity "Backstage"

        description "Volunteers needed to setup the room before
                     the meeting and afterward clean up the room."

        day "Sunday"
            shift "Setup"
                starting 5PM
                ending 6PM
            end
            shift "Cleanup"
                starting 8PM
                ending 9PM
            end
        end
    end

end
</pre>

<h2>Example 2</h2>
<p>
This example event script is for a one-day event that has distinct morning and afternoon sessions.
There is also preparation needed the day before.
There are 3 volunteers needed for setup on the Friday.
Saturday there are many volunteers needed: 3 for setup, 6 go-fors, and 3 for cleanup.
</p>
<pre>
event "MWS Cafeteria Planning"

    contact "Jamie Oliver"

    description "We will be presenting a morning presentation on food choice
                 and cafeterias at Waldorf schools in the US"

    day "Friday"
        date 2011-03-19
    end

    day "Saturday"
        date 2011-03-20
        hours "Presentations"
            starting 10AM
            ending 12PM
        end
        hours "Workshops & Planning"
            starting 1PM
            ending 5PM
        end
    end

    role "Cleanup"
    end

    role "Setup"
    end

    role "Coat-check"
    end

    role "Go-for"
    end

    activity "Backstage"
        day "Friday"
            shift "Setup"
                count 3
                starting 6PM
                ending 8PM
            end
            shift "Setup"
                count 3
                starting 8AM
                ending 10AM
             end
        end
        day "Saturday"
            shift "Setup"
                count 3
                starting 8AM
                ending 10AM
            end
            shift "Cleanup"
                count 3
                starting 5PM
                ending 7PM
            end
        end
    end

    activity "Onstage"
        day "Saturday"
            shift "Go-for"
                count 6
                starting 9AM
                ending 1PM
            end
            shift "Go-for"
                count 6
                starting 1PM
                ending 5PM
            end
            shift "Coat-check"
                starting 9AM
                ending 12PM
            end
        end
    end

end
</pre>

END

<p><i>This document was last updated Jan 1, 2026</i></p>
    </div>
</body>
</html>
