Overview
========

You can generate multiple file types from the documentation in order to create UML charts from PHP source code.

The following is a list of examples on how you can/would invoke the toop

## Generating a `dot` file for use with `Graphviz`

    phuml -r /httpd/apps/cdn/lib/ -graphviz /tmp/cdn.dot
    open -a Graphviz /tmp/cdn.dot

## Generating a PNG file using the `neato` processor

    phuml -r /httpd/apps/cdn/lib/ -graphviz -neato /tmp/cdn.png
    open /tmp/cdn.png

