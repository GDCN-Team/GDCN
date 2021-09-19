<template>
    <page-layout title="命令">
        <n-card>
            <n-grid class="mt-5" cols="1 768:2" x-gap="10">
                <n-grid-item>
                    <n-h2>Argument</n-h2>
                    <n-blockquote>
                        Argument 中文名为参数, 格式为: `--[key]=[value]`
                        <br>
                        特殊: 如果该项为数字则表示为空格后的第几个参数
                        <br>
                        使用例: !rate --stars=8 或 !rate 8
                        <n-text code>将关卡rate成8星(假设关卡没有被rate)</n-text>
                    </n-blockquote>
                </n-grid-item>
                <n-grid-item>
                    <n-h2>Option</n-h2>
                    <n-blockquote>
                        Option 中文名选项, 格式为 `-[flag]`
                        <br>
                        使用例: !rate -delete `删除Rating`
                    </n-blockquote>
                </n-grid-item>
            </n-grid>
            <n-divider></n-divider>
            <n-grid class="mt-5" cols="1 768:2" x-gap="10" y-gap="10">
                <n-grid-item>
                    <n-h5>账号评论命令</n-h5>
                    <n-collapse>
                        <n-collapse-item name="test" title="!test">
                            <n-h2>返回</n-h2>
                            <span class="font-bold">Worked!</span>
                        </n-collapse-item>
                    </n-collapse>
                </n-grid-item>
                <n-grid-item>
                    <n-h5>关卡评论命令</n-h5>
                    <n-collapse>
                        <n-collapse-item name="test" title="!test">
                            <n-h2>返回</n-h2>
                            <span class="font-bold">Worked!</span>
                        </n-collapse-item>
                        <n-collapse-item name="rate" title="!rate">
                            <n-h2>权限</n-h2>
                            <span class="font-bold">COMMAND_RATE_LEVEL</span>
                            <n-h2>参数</n-h2>
                            <n-table :bordered="false" :single-line="false">
                                <thead>
                                <tr>
                                    <th>名称</th>
                                    <th>参数名</th>
                                    <th>类型</th>
                                    <th>介绍</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Update</td>
                                    <td>u, update</td>
                                    <td>Option</td>
                                    <td>更新Rating</td>
                                </tr>
                                <tr>
                                    <td>Delete</td>
                                    <td>d, delete</td>
                                    <td>Option</td>
                                    <td>删除Rating</td>
                                </tr>
                                <tr>
                                    <td>Stars</td>
                                    <td>s, star, stars, 1</td>
                                    <td>Argument</td>
                                    <td>
                                        星星数量
                                        <n-text code>在unrated时为必要参数</n-text>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Featured</td>
                                    <td>f, featured</td>
                                    <td>Option</td>
                                    <td>
                                        标记Featured
                                        <n-text code>featured_score=1</n-text>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Featured Score</td>
                                    <td>fs, featured_score</td>
                                    <td>Argument</td>
                                    <td>
                                        featured分数
                                        <n-text code>分数越高, 在featured的排名越前</n-text>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Epic</td>
                                    <td>e, epic</td>
                                    <td>Option</td>
                                    <td>标记Epic</td>
                                </tr>
                                <tr>
                                    <td>Coin</td>
                                    <td>cv, coin_verified</td>
                                    <td>Option</td>
                                    <td>
                                        标记Coin状态
                                        <n-text code>铜币 -> 金币</n-text>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Demon Difficulty</td>
                                    <td>dd, demon_difficulty</td>
                                    <td>Argument</td>
                                    <td>
                                        标记Demon难度
                                        <n-text code>
                                            1=Easy Demon
                                            <br>
                                            2=Medium Demon
                                            <br>
                                            3=Hard Demon
                                            <br>
                                            4=Insane Demon
                                            <br>
                                            5=Extreme Demon
                                        </n-text>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Inverse</td>
                                    <td>i, inverse</td>
                                    <td>Option</td>
                                    <td>
                                        标记反转
                                        <n-text code>比如 !rate -u -i -f 表示取消关卡的featured状态</n-text>
                                    </td>
                                </tr>
                                </tbody>
                            </n-table>
                            <n-h2>常用</n-h2>
                            <n-blockquote>
                                rate关卡为 {x} 星: !rate {x}
                                <br>
                                更新stars为8: !rate -u --s=8
                                <br>
                                更新featured: !rate -u -f
                                <br>
                                更新epic: !rate -u -e
                                <br>
                                更新unFeatured: !rate -u -i -f
                                <br>
                                更新unEpic: !rate -u -i -e
                                <br>
                                更新银币: !rate -u -cv
                                <br>
                                更新铜币: !rate -u -i -cv
                                <br>
                                更新关卡为Insane Demon: !rate -u --dd=4
                            </n-blockquote>
                        </n-collapse-item>
                        <n-collapse-item name="set" title="!set">
                            <n-h2>参数</n-h2>
                            <n-table :bordered="false" :single-line="false">
                                <thead>
                                <tr>
                                    <th>名称</th>
                                    <th>参数名</th>
                                    <th>类型</th>
                                    <th>介绍</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Type</td>
                                    <td>1</td>
                                    <td>Argument</td>
                                    <td>
                                        类型
                                    </td>
                                </tr>
                                <tr>
                                    <td>Value</td>
                                    <td>2</td>
                                    <td>Argument</td>
                                    <td>
                                        值
                                    </td>
                                </tr>
                                </tbody>
                            </n-table>
                            <n-h2>常用</n-h2>
                            <n-blockquote>
                                设置关卡为Daily: !set as daily
                                <n-text code>需要权限: COMMAND_SET_AS_DAILY_LEVEL</n-text>
                                <br>
                                设置关卡为Weekly: !set as weekly
                                <n-text code>需要权限: COMMAND_SET_AS_WEEKLY_LEVEL</n-text>
                            </n-blockquote>
                        </n-collapse-item>
                    </n-collapse>
                </n-grid-item>
            </n-grid>
        </n-card>
    </page-layout>
</template>

<script>
import PageLayout from "../Components/PageLayout";
import {
    NBlockquote,
    NCard,
    NCollapse,
    NCollapseItem,
    NDivider,
    NGrid,
    NGridItem,
    NH2,
    NH5,
    NTable,
    NText
} from "naive-ui";

export default {
    name: "Commands",
    components: {
        PageLayout,
        NCard,
        NH5,
        NBlockquote,
        NDivider,
        NGrid,
        NGridItem,
        NTable,
        NH2,
        NText,
        NCollapse,
        NCollapseItem
    }
}
</script>
