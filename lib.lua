local lib = {}

lib.frame = 0

function lib.advance()
	emu.frameadvance()
	lib.frame = lib.frame + 1
end

function lib.invertInput(input)
	local inputInverted = {}
	for k,v in pairs(input) do
		inputInverted[k] = not v
	end
	return inputInverted
end

function lib.getColor(x,y)
	local color = {gui.getpixel(x,y)}
	return {r=color[1],g=color[2],b=color[3]}
end



-- Draw.
function lib.drawReticle(x,y)
	local color = {255,0,255}
	gui.setpixel(x-1,y-1,color)
	gui.setpixel(x  ,y-1,color);gui.setpixel(x  ,y-2,color);gui.setpixel(x  ,y-3,color)
	gui.setpixel(x+1,y-1,color)
	
	gui.setpixel(x-1,y  ,color);gui.setpixel(x-2,y  ,color);gui.setpixel(x-3,y  ,color)
	gui.setpixel(x+1,y  ,color);gui.setpixel(x+2,y  ,color);gui.setpixel(x+3,y  ,color)
	
	gui.setpixel(x-1,y+1,color)
	gui.setpixel(x  ,y+1,color);gui.setpixel(x  ,y+2,color);gui.setpixel(x  ,y+3,color)
	gui.setpixel(x+1,y+1,color)
end



-- Tests.
function lib.testForColorSimilarity(colorActual,colorExpected,tolerance)
	if math.abs(colorActual.r - colorExpected.r) <= tolerance and math.abs(colorActual.g - colorExpected.g) <= tolerance and math.abs(colorActual.b - colorExpected.b) <= tolerance then
		return true
	else
		return false
	end
end
function lib.testLocationForColorMatch(coordinate,colorExpected,tolerance)
	local colorActual = lib.getColor(coordinate[1],coordinate[2])
	lib.drawReticle(coordinate[1],coordinate[2]) -- dbg
	if lib.testForColorSimilarity(colorActual,colorExpected,tolerance) then
		return true
	else
		return false
	end
end



-- Debug.
function lib.dbgSampleColor(coordinate)
	lib.drawReticle(coordinate[1],coordinate[2])
	local color = lib.getColor(coordinate[1],coordinate[2])
	print(color)
end



-- Actions.
function lib.detectOrHold(detectionCondition,input)
	while true do
		if lib.detect(detectionCondition) == true then
			return
		end
		joypad.set(1,input)
		lib.advance()
	end
end
function lib.detectOrMash(detectionCondition,input)
	local inputInverted = lib.invertInput(input)
	while true do
		if lib.detect(detectionCondition) == true then
			return
		end
		lib.pressThenInvert(input)
	end
end
function lib.detectOrMashSlowly(detectionCondition,input,extraWaitCount)
	local inputInverted = lib.invertInput(input)
	while true do
		if lib.detect(detectionCondition) == true then
			return
		end
		lib.pressThenInvert(input)
		for i = 1,extraWaitCount,1 do
			lib.advance()
		end
	end
end
function lib.detectOrWait(detectionCondition)
	while true do
		if lib.detect(detectionCondition) == true then
			return
		end
		lib.advance()
	end
end
function lib.pressThenInvert(input)
	local inputInverted = lib.invertInput(input)
	joypad.set(1,input)
	lib.advance()
	joypad.set(1,inputInverted)
	lib.advance()
end



-- .
function lib.detect(detectionCondition)
	local detected = false
	if false then
		do end
	elseif detectionCondition == "all-white" then
		-- Key off first pixel.
		detected = lib.testLocationForColorMatch({0,0},{r=255,g=255,b=255},0)
	elseif detectionCondition == "all-black" then
		-- Key off first pixel.
		detected = lib.testLocationForColorMatch({0,0},{r=0,g=0,b=0},0)
	elseif detectionCondition == "keyboard" then
		-- Key off the cyan in the caps lock key.
		detected = lib.testLocationForColorMatch({10,102},{r=90,g=247,b=214},0)
	elseif detectionCondition == "textbox-exists" then
		-- Key off blue-ish color at the most opaque spot.
		detected = lib.testLocationForColorMatch({19,-39},{r=41,g=57,b=132},32)
	elseif (detectionCondition == "title-screen/option-hover") then
		-- Key off lime-ish color at top-left spot.
		detected = lib.testLocationForColorMatch({69,127},{r=214,g=239,b=90},32)
	elseif (detectionCondition == "title-screen/new-game-hover") then
		-- Key off lime-ish color at top-left spot.
		detected = lib.testLocationForColorMatch({69,53},{r=214,g=239,b=90},32)
	elseif detectionCondition == "title-screen/option/message-hover" then
		-- Key off lime-ish color at top-left spot.
		detected = lib.testLocationForColorMatch({12,-113},{r=222,g=247,b=99},64)
	elseif detectionCondition == "title-screen/option/message/no-wait-hover" then
		-- Key off lime-ish color at top-left spot.
		detected = lib.testLocationForColorMatch({209,-113},{r=189,g=206,b=99},64)
	elseif detectionCondition == "title-screen/option/delete-data-hover" then
		-- Key off lime-ish color at top-left spot.
		detected = lib.testLocationForColorMatch({12,-34},{r=222,g=247,b=99},64)
	elseif detectionCondition == "title-screen/option/delete-data/yes-hover" then
		-- Key off lime-ish color at top-left spot.
		detected = lib.testLocationForColorMatch({47,-85},{r=181,g=214,b=16},64)
	elseif detectionCondition == "title-screen/option/delete-data/no-hover" then
		-- Key off lime-ish color at top-left spot.
		detected = lib.testLocationForColorMatch({138,-85},{r=181,g=214,b=16},64)
	end
	if detected == true then
		print(detectionCondition)
	end
	return detected
end



return lib