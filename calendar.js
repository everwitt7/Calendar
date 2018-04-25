/* * * * * * * * * * * * * * * * * * * *\
*               Module 4              *
*      Calendar Helper Functions      *
*                                     *
*        by Shane Carr '15 (TA)       *
*  Washington University in St. Louis *
*    Department of Computer Science   *
*               CSE 330S              *
*                                     *
*      Last Update: October 2017      *
\* * * * * * * * * * * * * * * * * * * */

/*  This program is free software: you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation, either version 3 of the License, or
*  (at your option) any later version.
*
*  This program is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  You should have received a copy of the GNU General Public License
*  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

(function () {
	"use strict";

	/* Date.prototype.deltaDays(n)
	*
	* Returns a Date object n days in the future.
	*/
	Date.prototype.deltaDays = function (n) {
		// relies on the Date object to automatically wrap between months for us
		return new Date(this.getFullYear(), this.getMonth(), this.getDate() + n);
	};

	/* Date.prototype.getSunday()
	*
	* Returns the Sunday nearest in the past to this date (inclusive)
	*/
	Date.prototype.getSunday = function () {
		return this.deltaDays(-1 * this.getDay());
	};
}());

/** Week
*
* Represents a week.
*
* Functions (Methods):
*	.nextWeek() returns a Week object sequentially in the future
*	.prevWeek() returns a Week object sequentially in the past
*	.contains(date) returns true if this week's sunday is the same
*		as date's sunday; false otherwise
*	.getDates() returns an Array containing 7 Date objects, each representing
*		one of the seven days in this month
*/
function Week(initial_d) {
	"use strict";

	this.sunday = initial_d.getSunday();


	this.nextWeek = function () {
		return new Week(this.sunday.deltaDays(7));
	};

	this.prevWeek = function () {
		return new Week(this.sunday.deltaDays(-7));
	};

	this.contains = function (d) {
		return (this.sunday.valueOf() === d.getSunday().valueOf());
	};

	this.getDates = function () {
		var dates = [];
		for(var i=0; i<7; i++){
			dates.push(this.sunday.deltaDays(i));
		}
		return dates;
	};
}

/** Month
*
* Represents a month.
*
* Properties:
*	.year == the year associated with the month
*	.month == the month number (January = 0)
*
* Functions (Methods):
*	.nextMonth() returns a Month object sequentially in the future
*	.prevMonth() returns a Month object sequentially in the past
*	.getDateObject(d) returns a Date object representing the date
*		d in the month
*	.getWeeks() returns an Array containing all weeks spanned by the
*		month; the weeks are represented as Week objects
*/
function Month(year, month) {
	"use strict";

	this.year = year;
	this.month = month;

	this.nextMonth = function () {
		return new Month( year + Math.floor((month+1)/12), (month+1) % 12);
	};

	this.prevMonth = function () {
		return new Month( year + Math.floor((month-1)/12), (month+11) % 12);
	};

	this.getDateObject = function(d) {
		return new Date(this.year, this.month, d);
	};

	this.getWeeks = function () {
		var firstDay = this.getDateObject(1);
		var lastDay = this.nextMonth().getDateObject(0);

		var weeks = [];
		var currweek = new Week(firstDay);
		weeks.push(currweek);
		while(!currweek.contains(lastDay)){
			currweek = currweek.nextWeek();
			weeks.push(currweek);
		}

		return weeks;
	};
}

//March 2018
var currentMonth = new Month(2018, 2);

document.getElementById("next_month_btn").addEventListener("click", function(event){
	currentMonth = currentMonth.nextMonth();
	updateCalendar();
}, false);

document.getElementById("prev_month_btn").addEventListener("click", function(event){
	currentMonth = currentMonth.prevMonth();
	updateCalendar();
}, false);


function updateCalendar(){
	cur.innerHTML = toMonthName(currentMonth);
	var weeks = currentMonth.getWeeks();
	for(var w in weeks){
		var days = weeks[w].getDates();
		var tempWeek = numbers[2 * w + 1].childNodes;
		for(var i = 1; i < 8; i++) {
			tempWeek[i].innerHTML = dayToString(days[i-1]);
		}
	}
	if(weeks.length < 6) {
		numbers[11].hidden = true;
	} else {
		numbers[11].hidden = false;
	}
}

document.addEventListener("DOMContentLoaded", updateCalendar, false);

let cur = document.getElementById('currentdate');
let numbers = document.getElementById('daynumbers').childNodes;

function dayToString(curDay) {
	var tempStr = curDay.toISOString();
	//console.log(currentMonth.toISOString());
	tempStr = tempStr.substr(8, 2);
	return tempStr;
}

function toMonthName(month) {
	var monthName = "";
	if(month.month === 0) {
		monthName += "January "
	} else if (month.month === 1) {
		monthName += "February "
	} else if (month.month === 2) {
		monthName += "March "
	} else if (month.month === 3) {
		monthName += "April "
	} else if (month.month === 4) {
		monthName += "May "
	} else if (month.month === 5) {
		monthName += "June "
	} else if (month.month === 6) {
		monthName += "July "
	} else if (month.month === 7) {
		monthName += "August "
	} else if (month.month === 8) {
		monthName += "September "
	} else if (month.month === 9) {
		monthName += "October "
	} else if (month.month === 10) {
		monthName += "November "
	} else if (month.month === 11) {
		monthName += "December "
	}
	monthName += month.year;

	return monthName;
}
