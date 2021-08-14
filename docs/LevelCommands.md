## 关卡指令

---

#### **!rate {stars}** `需要权限: COMMAND_RATE_LEVEL`

| 名称 | 类型 | 介绍 | 参数名 |
| --- | --- | --- | --- |
| Update | Option | 更新Rating | u, update |
| Delete | Option | 删除Rating | d, delete |
| Stars | Argument | 星星数量 `在unrated时为必要参数` | s, star, stars |
| Featured | Option | 标记Featured `featured_score=1` | f, featured |
| Featured Score | Argument | featured分数 `分数越高, 在featured的排名越前` | fs, featured_score |
| Epic | Option | 标记Epic | e, epic |
| Coin Verified | Option | 标记Coin状态 `铜币 => 金币` | cv, coin_verified |
| Demon Difficulty | Argument | 标记Demon难度 `1=Easy Demon, 2=Medium Demon, 3=Hard Demon, 4=Insane Demon, 5=Extreme Demon` | dd, demon_difficulty |
| Inverse | Option | 标记反转 `比如 !rate -u -i -f 表示将关卡unfeatured` | i, inverse |

> 常用指令  
> rate关卡为 {x} 星: !rate {x}  
> 更新stars为8: !rate -u --s=8  
> 更新featured: !rate -u -f  
> 更新epic: !rate -u -e  
> 更新unFeatured: !rate -u -i -f    
> 更新unEpic: !rate -u -i -e  
> 更新银币: !rate -u -cv  
> 更新铜币: !rate -u -i -cv  
> 更新关卡为Insane Demon: !rate -u --dd=4
