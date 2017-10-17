# mostviewed
Most Viewed is a Craft 2 plugin to get entry view count last X days

It's an extension of the [Entry Count plugin developed by PutYourLightsOn](https://github.com/putyourlightson/craft-entry-count)

The «Days to accumulate» setting will be used when runnning the cleanup task. This also means the count variable will show number of views last X days.


##Twig Tags

**count(entry.id)**

	{% set count = craft.entryCount.count(entry.id) %}

	Entry count: {{ count }}

**entries**

	{% set countedEntries = craft.entryCount.entries %}

	{% for entry in countedEntries %}
		{% set count = craft.entryCount.count(entry.id) %}
		{{ entry.title }} ({{ count }} views)
	{% endfor %}

**increment(entry.id)**

	{% do craft.entryCount.increment(entry.id) %}


##Roadmap
* Set up action for cron jobs/manual cleanup
* Set up task index table for better task handling
* Allow twig variable to accept second parameter to set number of days to fetch view count from
