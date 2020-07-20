--DeSmuME (joypad lib): debug, R, L, X, Y, A, B, start, select, up, down, left, right, lid
--DeSmuME (stylus lib): x, y, touch

-- gui.text(8, -60, tostring(0))

-- The menuing system seems to have DAS pre-charge (NES Tetris).

lib = dofile("lib.lua")

-- Use toggle this flag to use a debugging color sampler on the current screen.
if false then
	while true do
		lib.dbgSampleColor({10,102})
	end
	do return end
end

-- Reset.
lib.pressThenInvert({L=true,R=true,select=true,start=true})
lib.detectOrWait("all-white")
lib.detectOrWait("all-black")

-- Enter title screen.
lib.detectOrMash("all-white",{A=true})

-- Enter options menu.
lib.detectOrHold("title-screen/option-hover",{down=true})
lib.pressThenInvert({A=true})

-- Delete data. This menu is strange - it cannot be DAS pre-charged and it buffers inputs.
lib.detectOrHold("title-screen/option/delete-data-hover",{up=true})
lib.pressThenInvert({A=true})
lib.detectOrWait("title-screen/option/delete-data/no-hover")
lib.detectOrMashSlowly("title-screen/option/delete-data/yes-hover",{left=true},10)
lib.pressThenInvert({A=true})
lib.detectOrWait("title-screen/option/delete-data/no-hover")
lib.detectOrMashSlowly("title-screen/option/delete-data/yes-hover",{left=true},10)
lib.pressThenInvert({A=true})
lib.detectOrMashSlowly("title-screen/option/delete-data-hover",{A=true},30)

-- Change message speed to "no wait".
lib.detectOrHold("title-screen/option/message-hover",{up=true})
lib.pressThenInvert({A=true})
lib.detectOrHold("title-screen/option/message/no-wait-hover",{right=true})
lib.pressThenInvert({A=true})

-- Exit options menu.
lib.pressThenInvert({B=true})
lib.pressThenInvert({A=true})

-- Enter new game.
lib.detectOrHold("title-screen/new-game-hover",{down=true})
lib.pressThenInvert({A=true})

-- Mash through lore.
lib.detectOrMash("keyboard",{A=true})