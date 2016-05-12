# SWAM [by Nicola Bombaci](http://nicolabombaci.com/ "Nicola Bombaci Webpage")
### Short Web Acronyms Merged
<img style="float:right" src="./swam.png">
### [Link to the official page](http://nicolabombaci.com/project/swam "SWAM") - [LICENSE](http://www.tldrlegal.com/l/afl3 "AFL3")

SWAM (Short Web Acronyms Merged) is a great alternative for the Front-End developers. HTML is often filled with tags that during large works, generate projects slow and difficult to understand, for this reason SWAM promises to catalog all the HTML tags to have a website backward compatible, fluid and dynamics.
The language is python like and require the presence of the tabs. Every line is considered like a node of tree, who is on top is the father, who is under the father is a son and so on

## Try the live code [HERE](http://nicolabombaci.com/project/code/swam.php "SWAM Live Code")

You can easily try the language and understand the syntax of SWAM. It's easy, it's fast, it could be yours. Let's click to discover. It's free

## How to
### Install
Download the project from [Github](https://github.com/Nilsbb/swam "GitHub") and copy `swam-min.php` in your folder

Add in your *php* file the **library**

`include './path/swam-min.php';`

Get the contents from a file

`$code = file_get_contents("./path/string.swa");`

And print your parsed file

`echo $swam->parse($code);`

And this is it!
### Programming

Every time you will create a new SWAM file (.swa), you can compile it only by pass through the piece of code into the class of **swam** and exectue the parsing `$swam->parse($string)`. A little debugger will show you the status of your work.

There is a **debug mode** that you can easily activate, opening the file `swam-min.php` and at start you can see the variable `$debug` set on false. Turn on `true` and you can see how SWAM is working. It is very useful to check and fix some mistakes inside your code.


## Comments
Write a comment is simple. It can just work only if you are using a whole line. The syntax used is like every language, you must use **//** and so on.

An example

> **on** div

>> **//** This line is a comment

>> **on** p

>>> Here I am

Be careful if you are typing a double slash inside a line with code, this will be removed and could cause error parsing

An example

> **on** p **//** I cannot stay here

>> Ops.

## Structure

### The on tag

In HTML to open some tag is used the syntax

```html
<html></html>
```

In SWAM to open the tag you can easily use the syntax

> **on** html

### Container

ON is a general container tag. It is helpful for every kind of tags like: **div** , **section** , **form** , **p** , and others. If you want to create a multilevel tag, you must start the new paragraph with one more tab than the previous element.

*Example*

> **on** div

>> **on** section

This will produce:

```html
<div>
  <section></section>
</div>
```

### In line

If you want to insert something inside an tag, you must start a new paragraph with one or more tabs than the previous element. You will write in the same tag until the number of tabs will be less than the father or a new tag has been inserted in.

*Example*

> **on** p

>> Hello World

>>> This is a test

>> **on** strong

>>> cool

> **on** h1

>> Here

This will produce:

```html
<p>Hello World This a test <strong>cool</strong></p>
<h1>Here</h1>
```

### Example

> **on** html

>> **on** head

>>> **on** title

>>>> SWAM

>> **on** body

>>> **on** p

>>>> Hello World

>>> **on** div

>>>> Nothing

This will produce:

```html
<html>
    <head>
        <title>SWAM</title>
    </head>
    <body>
        <p>Hello World</p>
        <div>Nothing</div>
    </body>
</html>
```

## JavaScripts
**SWAM** is fully compatible with JavaScript

> **on** script type="text/javascript"

>> var user = {
>> name: 'Dolly'};

>>sprintf('Hello %(name)s', user);

## Fast attributes
 **SWAM** is fully backward compatible. You can use instead a ON tag, the same syntax of HTML.

> *Example* : **on** div **id**="new" **style**="margin-top:20em"

The important thing is use the white space only for declare a new value and the tab only for the row (the hierarchy of the nodes)

To give a fast input I've introduced 2 important symbol:

1.	**#**

    > This tag substitue the syntax **id="myid"** with **#myid**
2.	**.**

    > This tag substitue the syntax **class="1stclass 2ndclass 3rd-class"** with **.1stclass.2ndclass.3rd-class**

## Example

> on html

>> on head

>>>on meta name="Welcome to SWAM"

>> on body

>>> on strong
>>>> Example

>>> on section

>>>> on div #id1
>>>>> on p
>>>>>> Section 1

>>>>> on div #id2
>>>>>> on p
>>>>>>> Section 2

>>>>>> on div #id3
>>>>>>> on p style="color:white"
>>>>>>>> Section 3

## Results:

```html
<html>
    <head>
        <meta name="Welcome to SWAM"
    </head>
    <body>
        <strong>Example</strong>
        <section>
            <div id="id1">
                <p>Section 1</p>
                <div id="id2">
                    <p>Section 2</p>
                    <div id="id3">
                        <p style="color:white">Section 3</p>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>
```
