<?php
class ArrayUtils
{
    // usage: $user = ArrayUtils::find($users, fn($u) => $u->id == 2);
    public static function find(array $items, callable $predicate): mixed
    {
        foreach ($items as $item) {
            if ($predicate($item)) {
                return $item;
            }
        }
        return null;
    }
}
?>