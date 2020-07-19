# EtrianOdysseyTAS  
Etrian Odyssey I TAS Programming Language  
  
## What Is It?  
This is a simple programming language that compiles to DeSmuME's .dsm file. You write some code in this language, then compile it with the PHP tool, saving the output as a .dsm file that you then load into DeSmuME with Etrian Odyssey I running.  
  
## Why Is It?  
(1) We have complete RNG manipulation figured out for Etrian Odyssey, and it's doable in real time. Because of this, RTA notes end up looking like a TAS, since this game is mostly grid-walking and menuing.  
(2) This game is too lengthy and mundane for frequent real time speedruns.  

## Compiling Code In Your Command-Line Interface  
(1) Have PHP installed (as far as I know, Apple OSs have PHP installed by default).  
(2) Example : cd ~/Documents/EtrianOdysseyTAS/ && php -f compiler.php somename.dsm shelf/library1.txt main.txt (first argument is output name, following arguments are code files, combined in left-to-right order) (another example can be found in shellScript.txt)  
  
## Notes And Warnings  
• The information in your DeSmuME Battery folder impacts the TAS. Because wait times in EOI take into account past inputs and the state of the Battery folder, it will almost definitely de-sync the TAS at some point. The TAS should be run with a missing Battery file [DeSmuME will generate a default one for you].  
• Debugging a TAS in DeSmuME can be difficult. On the Apple OS versions, there's a handy little Execution Control window. There's a command called "Jump to frame number:". Its name is misleading. Think of it as "Fast-forward up until just before frame number:". If you load a TAS and then jump 1000 frames, DeSmuME will run the first 1000 input frames as fast as possible and then drop you off immediately before frame 1000. Use the #lbl instruction in your code to figure out where your breakpoints will be, pause the emulator, jump to that spot, and start debugging.  
• The compiler will not help you very much regarding syntax errors. My hope is that this language is simple enough that you simply won't make any mistakes.  
• The name of the function that gets called in order to make the .dsm is "main". Remember to include a #fxn[main] command at the top of your main code file, otherwise nothing will be generated.  
• Currently, functions are evaluated as they're parsed - in a single pass. This means that you must define functions before you use them in code.  
  
## Language Specification  
This is how the language should behave, not necessarily how it actually behaves.  
  
---  
  
### Short Instructions  
  
#### Button Push[es] On A Frame  
Example : A  
Example : UR  
A consecutive grouping of one or more of the following letters : RLDUTSBAYXWEG.  
• RLDU : right,left,down,up on directional pad  
• T    : s[T]art  
• S    : [S]elect  
• BAYX : four thumb buttons  
• WE   : left[West],right[East] triggers  
• G    : debu[G] (I don't know what this does - some DeSmuME thing)  
Each mentioned button will be pushed at the same time.  
  
#### Wait Instruction  
Example : \_  
One underscore.  
• \_ : wait for one frame, not sending any inputs whatsoever  
  
---  
  
### Long Instructions  
  
#### Stylus Up/Down At Specified Position  
Example : @sty[60,60,1]  
Send a stylus command for the touchscreen at the [x:0-255,y:0-191,z:0-1] point. Northwest corner of the touchscreen is [0,0]. Pressing gives a z value of 1, not using the stylus gives a z value of 0.  
  
#### Manually Control All Signals For A Frame  
Example : @all[0][AU][147,065,1]  
Send any combination of signals. Box 1 is the c flag, box 2 is the button list, box 3 is stylus information.  
  
#### Reset The Console  
Example : @rst  
Reset the console. [!!!] Not sure whether this is a soft or hard reset.  
  
#### Breakpoint Labels  
Example : #lbl[B1F_Encounter_23_START]  
Sets an internal variable to mark this point in code. After compiling, the tool will inform you of the frame number that each label exists on. The compiler will automatically insert a START and END breakpoint for your convenience. Label names may only contain uppercase and lowercase letters, numbers, and underscores.  
  
#### Defining Functions  
Example : #fxn[new_game]  
Creates/resets a code bin with the given name, will write future lines to that bin. Function names may only contain uppercase and lowercase letters, numbers, and underscores.  
  
#### Calling Functions  
Example : #cal[new_game]  
Calls a defined function/routine. Inserts code from the corresponding code bin to this point in the file. Function names may only contain uppercase and lowercase letters, numbers, and underscores.  
  
---  
  
### Loops  
Example : 60{A \_}  
Example : 12{\_}  
Runs whatever is inside of the curly braces the specified number of times. [!!!] Currently, only single-level[non-nested] loops are supported for the sake of ease of implementation.  
  
---  
  
### Comments  
Example : // move cursor to Ambush, use it, exit menu  
Used to make the TAS code more readable, has no effect on code other than the // and everything after it being removed at the start of compilation.  
  