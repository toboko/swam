# SWAM [by Nicola Bombaci](http://nicolabombaci.com/ "Nicola Bombaci Webpage")
#### Short Web Acronyms Merged
<img style="float:right" src="./swam.png">
### [Link to the official page](http://nicolabombaci.com/project/swam "SWAM") - [LICENSE](http://www.tldrlegal.com/l/afl3 "AFL3")

**SWAM** is a great alternative to modern languages and formatting web programming such as HTML and PHP. These languages are often filled with tags that during large works, generate projects slow and difficult to understand, for this reason **SWAM** promises to catalog all the HTML tags and implement the optimal PHP to have a website or a web application backward compatible, fluid and dynamics.

The language is python like and require the presence of the tabs. Every line is considered like a node of tree, who is on top is the father, who is under the father is a son and so on

Try the live code [HERE](http://nicolabombaci.com/project/code/swam.php "SWAM Live Code")

## Structure

#### Container

**on** *tag*

ON is a container tag. It is helpful for **div** , **section** , **form** and all kind of elements that are containing other elements. If you want to insert something inside an ON tag for example you must start a new paragraph with one more tab than the previous element.

*Example*

>**on div**

>>**on section**

This will produce:

```html
<div><section></section></div>
```

#### One line

**in** *tag*

IN open and close in one line. It is helpful for **p** , **h1** , **script** , **meta** , **style** , **php script** and all kind of elements that containing text or don't accept other things inside themself. If you want to insert something inside an IN tag for example you must start a new paragraph with one more tabs than the previous element, if the next paraghraps will have the same number of tabs you are still writing in the same in tag until the number of tabs will be less than the father.

*Example*

>**in p**

>>**Hello World**

>>**This is a test**

This will produce:

```html
<p>Hello World this a test</p>
```

#### Example On and In tag

**on html**

>**on head**

>>**in meta**

>**on body**

>>**in p**

>>>**Hello World**

>>**on div**


This will produce:

```html
<html>
    <head>
        <meta>
    </head>
    <body>
        <p>Hello World</p>
        <div></div>
    </body>
</html>
```

## General Values

### Fast attributes
 **SWAM** is fully backward compatible. You can use instead a ON tag or a IN tag, the same syntax of HTML.

> *Example* : **on** div **id**="new" **style**="margin-top:20em"

The important thing is use the white space only for declare a new value and the tab only for the row (the hierarchy of the nodes)

To give a fast input I've introduced 3 important symbol:

1.  **#**

    > This tag substitue the syntax **id="myid"** with **#myid**

2.  **@**
    > This tag substitue the syntax **class="myclass"** with **@myclass**
3.  **$**
    > This tag introduce a php variable inside an **ON** or an **IN** tag, **not outside**. When you will insert it leave a white space before and after the elements around it

    >*Example :* **on** div element=" **$variable** "

### Internal tag
Some attributes like **a**, **strong**, **little**, **big**, etc. can be inserted inside a **in** tag. For this reason, when you are typing a text and you want a link or a font more "strong" you must use the key **|**tag. Next words you will insert will recognize like attributes of this tag. Finishing this step, you can introduce the content using squares brackets **[Contents of the tag]**

*P.s Remember to leave a space after every single words. You could do a parsing fault*

*Example:*

**Error**
> |ahref=""[Try].Ok let's go

> |a href=""[Try].Ok let's go

> |a href="" [Try]. Ok let's go

**Correct**
> |a href="" [Try] . Ok let's go

**Example**
> **in** p
>> This is an example to |a href="example.php" [Understand]

>> My |strong [Idea] . Enjoy it

This will produce
```html
  <p>This is an exanoke to <a href="example.php">Understand</a>
    My <strong>Idea</strong>. Enjoy it </p>```

## PHP and Scripts
**SWAM** is fully compatible with the PHP (you must declare **in** php) and the other language like Java (you must declare **in** script)
>**in** php

>>$max = 32;

>>$set = true;

>>if($set) echo $max;


>**in** script type="text/javascript"

>>var user = {
>>name: 'Dolly'};

>>sprintf('Hello %(name)s', user);

## Example
on html
>on head

>>in meta name="Welcome to SWAM"

>>in style type="text/css"
>>>p{padding: 15px 0 0 15px}

>>>\#id1{background:red;width:415px;height:300px}

>>>\#id2{background:orange;width:315px;height:200px;margin:auto}

>>>\#id3{background:black;width:215px;height:100px;margin:auto}

>on body

>>in strong
>>>Example

>>on section

>>>on div #id1
>>>>in p
>>>>>Section 1

>>>>on div #id2
>>>>>in p
>>>>>>Section 2

>>>>>on div #id3
>>>>>>in p style="color:white"
>>>>>>>Section 3

## Results:

```html
<html>
    <head>
        <meta name="Welcome to SWAM">
        <style type="text/css">p{padding: 15px 0 0 15px} #id1{background:red;width:415px;height:300px} #id2{background:orange;width:315px;height:200px;margin:auto} #id3{background:black;width:215px;height:100px;margin:auto}
        </style>
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
</html>```
