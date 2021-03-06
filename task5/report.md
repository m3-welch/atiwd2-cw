### Advanced Topics In Web Development 2
### Morgan Welch
### 17006647
### 2020 - 2021
---
# Streaming Parsers vs DOM Parsers
## Introduction
When it is required to read in a file on disk, process it, and then output data (either to a file, or to some other output format),
there are a few options available. The most prominent two being Data Stream Parsers and Document Object Model (DOM) Parsers.

## Data Stream Parsers
Data Stream Parsers take the file at face-value. They do not try and infer any type of relationship between sections of the file. Instead,
the parser can read the data into memory in chunks. The most common form of this is the ability to read the file line by line.

The main benefit of this method is that it does not require the document to be heavily processed or observed before the program can make use
of it. This in turn means that code using this parsing method can run a lot faster, as the data can be read almost fluidly and more similarly
to how a human would read a document such as a book. It also provides the benefit that for large files, the parser does not require the file
to be loaded into memory before processing. This is a major benefit for programs that make use of extremely large data files (for example files
that are in excess of a gigabite).

The downside of this method on the other hand, is that it means that the parser will have no idea about what comes next or what came previously. 
This can be a particularly useful method of processing relational data, so its loss could mean more work is needed by the programmer or code
working on the file in order to cleverly traverse the document.

An example of this tool in PHP is the `fopen()`, `fgets()` and `fclose()` family, which can create a data stream from a file and allows the code
to traverse the file line by line.

## DOM Parsers
DOM Parsers read the entire file into memory first, and then try and infer relationships using the Document Object Model (DOM). This means
that this parsing method is only suitable for files with a DOM structure, such as XML and HTML. The benefit of this method is that the
parser is aware of the relationships between the elements and nodes on the DOM, allowing the code to traverse the document is a more
intelligent manner.

The ultimate benefit of this method is that relational documents can be traversed quickly and programmatically. For example, a programmer could
use a DOM parser to retrieve all child elements of a node that have a particular attribute. This can mean that the code to retrieve these
elements can be written in much fewer lines and be much easier for a human to understand.

The main downside however, is that the whole file must be loaded into memory and parsed in its entirety before the code can utilise the file.
While the time to do this on smaller files may be comparable to using a Data Stream Parser, when the file size being loaded gets larger, this
method will take a lot longer.

An example of this tool in PHP is the `SimpleXML` toolset, which allows an XML compliant file to be loaded into memory, and traversed intelligently
and succinctly.

## Conclusion
While DOM File Parsers feel the best to use due to their superior traversal methods, they are not always the correct choice for large data files.
Using a DOM parser on a web application may make the application feel slow and sluggish, so perhaps a Data Stream Parser is the appropriate choice
for that use case.

# Links

## NOTE: Submitted zip folder is in a different format/filestructure and the file paths in the code reflect that

[GitHub](https://github.com/m3-welch/atiwd2-cw)

[README.md (including usage instructions)](https://github.com/m3-welch/atiwd2-cw/blob/master/README.md)

---
## [Task 1](https://github.com/m3-welch/atiwd2-cw/tree/master/task1)
---
[extract-to-csv.php](https://github.com/m3-welch/atiwd2-cw/blob/master/task1/extract-to-csv.php)

[extract-to-csv.phps](https://github.com/m3-welch/atiwd2-cw/blob/master/task1/extract-to-csv.phps)

---
## [Task 2](https://github.com/m3-welch/atiwd2-cw/tree/master/task2)
---
[normalize-to-xml.php](https://github.com/m3-welch/atiwd2-cw/blob/master/task2/normalize-to-xml.php)

[normalize-to-xml.phps](https://github.com/m3-welch/atiwd2-cw/blob/master/task2/normalize-to-xml.phps)

[air-quality.xsd](https://github.com/m3-welch/atiwd2-cw/blob/master/task2/air-quality.xsd)

---
## [Task 3](https://github.com/m3-welch/atiwd2-cw/tree/master/task3)
---
[README.md](https://github.com/m3-welch/atiwd2-cw/blob/master/task3/README.md)

[index.php](https://github.com/m3-welch/atiwd2-cw/blob/master/task3/index.php)

[index.phps](https://github.com/m3-welch/atiwd2-cw/blob/master/task3/index.phps)

[stylesheet.css](https://github.com/m3-welch/atiwd2-cw/blob/master/task3/stylesheet.css)

[certainTimeOfDayData.php](https://github.com/m3-welch/atiwd2-cw/blob/master/task3/certainTimeOfDayData.php)

[certainTimeOfDayData.phps](https://github.com/m3-welch/atiwd2-cw/blob/master/task3/certainTimeOfDayData.phps)

[allLevels6Stations.php](https://github.com/m3-welch/atiwd2-cw/blob/master/task3/allLevels6Stations.php)

[allLevels6Stations.phps](https://github.com/m3-welch/atiwd2-cw/blob/master/task3/allLevels6Stations.phps)

[sites_list.php](https://github.com/m3-welch/atiwd2-cw/blob/master/task3/sites_list.php)

[sites_list.phps](https://github.com/m3-welch/atiwd2-cw/blob/master/task3/sites_list.phps)

---
## [Task 4](https://github.com/m3-welch/atiwd2-cw/tree/master/task4)
---
[README.md](https://github.com/m3-welch/atiwd2-cw/blob/master/task4/README.md)

[index.php](https://github.com/m3-welch/atiwd2-cw/blob/master/task4/index.php)

[index.phps](https://github.com/m3-welch/atiwd2-cw/blob/master/task4/index.phps)

[stylesheet.css](https://github.com/m3-welch/atiwd2-cw/blob/master/task4/stylesheet.css)

[getData.php](https://github.com/m3-welch/atiwd2-cw/blob/master/task4/getData.php)

[getData.phps](https://github.com/m3-welch/atiwd2-cw/blob/master/task4/getData.phps)

[googleMapsKey.example.php](https://github.com/m3-welch/atiwd2-cw/blob/master/task4/googleMapsKey.example.php)

[googleMapsKey.example.phps](https://github.com/m3-welch/atiwd2-cw/blob/master/task4/googleMapsKey.example.phps)

[sites_list.php](https://github.com/m3-welch/atiwd2-cw/blob/master/task4/sites_list.php)

[sites_list.phps](https://github.com/m3-welch/atiwd2-cw/blob/master/task4/sites_list.phps)

---
## [Task 5](https://github.com/m3-welch/atiwd2-cw/tree/master/task5)
---
[REPORT.md](https://github.com/m3-welch/atiwd2-cw/blob/master/task5/REPORT.md)

---
## Data CSV File
[air-quality-data-2004-2019.zip](https://fetstudy.uwe.ac.uk/~p-chatterjee/2020-21/modules/atwd2/assignment/air-quality-data-2004-2019.zip)

---
## Extra Tools Created (Not required)
---
[extra_tools](https://github.com/m3-welch/atiwd2-cw/tree/master/extra_tools)

[count_lines.php](https://github.com/m3-welch/atiwd2-cw/blob/master/extra_tools/count_lines.php)

[count_lines.phps](https://github.com/m3-welch/atiwd2-cw/blob/master/extra_tools/count_lines.phps)

[accuraccy_benchmark.php](https://github.com/m3-welch/atiwd2-cw/blob/master/extra_tools/accuracy_benchmark.php)

[accuraccy_benchmark.phps](https://github.com/m3-welch/atiwd2-cw/blob/master/extra_tools/accuracy_benchmark.phps)

[validate_xml_xsd.php](https://github.com/m3-welch/atiwd2-cw/blob/master/extra_tools/validate_xml_xsd.php)

[validate_xml_xsd.phps](https://github.com/m3-welch/atiwd2-cw/blob/master/extra_tools/validate_xml_xsd.phps)

[run](https://github.com/m3-welch/atiwd2-cw/blob/master/extra_tools/run)

[resetfiles](https://github.com/m3-welch/atiwd2-cw/blob/master/extra_tools/resetfiles)

# Further Visiualisation
My current submission for Task 4 is a heatmap displayed over the Google Maps representation of Bristol. While I think that this visualisation is
powerful as it is easy to read, the data being displayed is only the average data for each site for each month. I think that a more powerful
interpretation would be to have every single data point being rendered on the heatmap, and then simply displaying the average in the tooltip.
This is something that the Google Maps API allows, but something I decided not to do, however I think it would be a good direction for further
development.

I also would suggest a change to the line graph on task 3.2. The use can currently only view one site at a time by clicking on the site in the site
list. This is not very useful for comparing the pollutant levels between the sites. I think a further improvement could be to either find a way of
displaying all 6 of the sites' data on a single chart, or perhaps tiling the 6 charts for each site on the page to allow the user to view all the
data simultaneously.