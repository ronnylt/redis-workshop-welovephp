local items
local itemId
local result = {}

items = redis.call('SMEMBERS', KEYS[1])

for i = 1, #items do
   itemId = items[i]

   table.insert(result, #result+1, itemId)
   table.insert(result, #result+1, redis.call('SCARD', KEYS[2]..itemId))
end

return result