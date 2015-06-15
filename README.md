# azur-archives

Wordpress Plugin Archive per Shortcode

## Usage
On any Page use:
```
[azur-archives]
```


## Recommended CSS
```CSS 
.azur-archive ul {
  padding: 0;
  margin-left: 0;  
}

.azur-archive ul li {
  margin-left: 0;
  list-style: none;
}

.azur-archive ul li + li {
  margin-top: 1em;  
}

.azur-archive ul time {   
  color: #777;
  display: block;
  letter-spacing: 1px;
  text-transform: uppercase; 
  margin: 0;
}

/* --- tablets in portrait mode --- */
@media screen and (min-width: 768px) {
  .azur-archive ul li + li {
    margin-top: 0;  
  }
  .azur-archive ul time {   
    float:right;
    margin-left: 1em;
  }
}
``` 