# Coverage of the legacy gateway inventory

This inventory records 59 gateway handler profiles from 71 gateway-tree source entries (70 `.php` files plus the `.php.bk` backup): 36 `CCN`, 9 `Free`, 7 `CCN CHARGED`, 2 root handlers, and 5 mass-handler files. The remaining 12 files are listed as excluded below.

`gateways/legacy_profiles.json` contains metadata only: source path, observed command alias, broad operation category, explicitly named labels, and a state note. It is an offline replacement profile catalog, not a translation of the remote PHP logic. It does not make payment requests, check cards, or implement provider APIs. The profile labels are copied only when named in the corresponding source; they are not verified protocol identifications. No parity with the original PHP behavior is claimed.

| Source file | Profile ID | Command alias | Legacy state |
|---|---|---|---|
| `CCN/3dali.php` | `ccn-3dali` | `3d` | `handler` |
| `CCN/Holger.php` | `ccn-holger` | `hg` | `handler` |
| `CCN/Inu.php` | `ccn-inu` | `inu` | `handler` |
| `CCN/Kroon.php` | `ccn-kroon` | `ko` | `handler` |
| `CCN/Lytos.php` | `ccn-lytos` | `ly` | `handler` |
| `CCN/Reality deshuso.php` | `ccn-reality-deshuso` | `rl` | `obsolete` |
| `CCN/Rita Gate.php` | `ccn-rita-gate` | `rt` | `handler` |
| `CCN/Stranger.php` | `ccn-stranger` | `st` | `handler` |
| `CCN/Tang.php` | `ccn-tang` | `tg` | `handler` |
| `CCN/Yis.php` | `ccn-yis` | `ys` | `handler` |
| `CCN/ali.php` | `ccn-ali` | `ali` | `handler` |
| `CCN/ax.php` | `ccn-ax` | `ax` | `handler` |
| `CCN/br (1).php` | `ccn-br-1` | `br` | `handler` |
| `CCN/ct.php` | `ccn-ct` | `ct` | `handler` |
| `CCN/dn deshuso.php` | `ccn-dn-deshuso` | `dm` | `obsolete` |
| `CCN/dr.php` | `ccn-dr` | `ll` | `handler` |
| `CCN/ewa deshuso.php` | `ccn-ewa-deshuso` | `dr` | `obsolete` |
| `CCN/gb.php` | `ccn-gb` | `gb` | `handler` |
| `CCN/iz.php` | `ccn-iz` | `iz` | `handler` |
| `CCN/kd.php` | `ccn-kd` | `kd` | `handler` |
| `CCN/lw Formato Update.php` | `ccn-lw-formato-update` | `lw` | `handler` |
| `CCN/michi.php` | `ccn-michi` | `mt` | `handler` |
| `CCN/ms.php` | `ccn-ms` | `ms` | `handler` |
| `CCN/never deshuso.php` | `ccn-never-deshuso` | `ne` | `obsolete` |
| `CCN/nl deshuso.php` | `ccn-nl-deshuso` | `nl` | `obsolete` |
| `CCN/pp.php` | `ccn-pp` | `pp` | `handler` |
| `CCN/ps.php` | `ccn-ps` | `ps` | `handler` |
| `CCN/pwf deshuso.php` | `ccn-pwf-deshuso` | `pw` | `obsolete` |
| `CCN/roxy deshuso.php` | `ccn-roxy-deshuso` | `rx` | `obsolete` |
| `CCN/sd.php` | `ccn-sd` | `sd` | `handler` |
| `CCN/sf.php` | `ccn-sf` | `sf` | `handler` |
| `CCN/sm.php` | `ccn-sm` | `sm` | `handler` |
| `CCN/tlv.php` | `ccn-tlv` | `tlv` | `handler` |
| `CCN/vtc.php` | `ccn-vtc` | `vtc` | `handler` |
| `CCN/wf.php` | `ccn-wf` | `wf` | `handler` |
| `CCN/Amx_Coookie.php` | `ccn-amx-cookie` | `mxc` | `fragment` |
| `Free/at.php` | `free-at` | `at` | `handler` |
| `Free/au.php` | `free-au` | `au` | `handler` |
| `Free/ay.php` | `free-ay` | `an` | `handler` |
| `Free/chk.php` | `free-chk` | `chk` | `handler` |
| `Free/kl replace.php` | `free-kl-replace` | `kl` | `handler` |
| `Free/lazuu.php` | `free-lazuu` | `zs` | `handler` |
| `Free/mbt.php` | `free-mbt` | `mbt` | `handler` |
| `Free/nks.php` | `free-nks` | `nks` | `handler` |
| `Free/sfp.php` | `free-sfp` | `shp` | `handler` |
| `CCN CHARGED/Queen.php` | `ccn-charged-queen` | `qn` | `handler` |
| `CCN CHARGED/adriana.php` | `ccn-charged-adriana` | `adr` | `handler` |
| `CCN CHARGED/ar.php` | `ccn-charged-ar` | `00` | `fragment` |
| `CCN CHARGED/krispy.php` | `ccn-charged-krispy` | `kr` | `handler` |
| `CCN CHARGED/lucky.php` | `ccn-charged-lucky` | `lk` | `handler` |
| `CCN CHARGED/mx.php` | `ccn-charged-mx` | `mx` | `handler` |
| `CCN CHARGED/sfpz.php` | `ccn-charged-sfpz` | `pz` | `handler` |
| `mass/mass1.php` | `mass-mass1` | `mass1` | `handler` |
| `mass/mass2.php` | `mass-mass2` | `mass2` | `handler` |
| `mass/mass3.php` | `mass-mass3` | `mass3` | `handler` |
| `mass/mass4 replace.php` | `mass-mass4-replace` | `mass4` | `fragment` |
| `mass/mass5.php` | `mass-mass5` | `mass8` | `handler` |
| `vbv.php` | `root-vbv` | `vbv` | `handler` |
| `za.php` | `root-za` | `za` | `handler` |

Excluded files:

| Source file | Reason |
|---|---|
| `_base.php` | Shared helper/configuration file, not an individual payment gateway handler. |
| `Funtcion/Key.php` | Bot key and access helper, not a payment gateway handler. |
| `Funtcion/Premium.php` | Membership helper, not a payment gateway handler. |
| `Funtcion/extra.php` | Bot utility helper, not a payment gateway handler. |
| `Funtcion/grouptime.php` | Group membership timing helper, not a payment gateway handler. |
| `Funtcion/premiuntime.php` | Membership timing helper, not a payment gateway handler. |
| `Funtcion/price.php` | Pricing helper, not a payment gateway handler. |
| `Funtcion/rand.php` | Random-data helper, not a payment gateway handler. |
| `Funtcion/sdd.php` | Bot utility helper, not a payment gateway handler. |
| `Funtcion/start.php` | Bot startup helper, not a payment gateway handler. |
| `mass/bin.php` | BIN-information helper, not a mass payment handler. |
| `mass/mass1.php.bk` | Backup copy of mass1.php, excluded to avoid counting duplicate handler code. |

The catalog keeps uncertain entries explicit: `CCN/Amx_Coookie.php` and `CCN CHARGED/ar.php` are fragments with no inferred command alias; `mass/mass4 replace.php` is likewise a replacement fragment. Obsolete entries retain aliases only where the source inventory supplied them. `mass/mass5.php` dispatches the observed alias `mass8`.

Mass profiles are inventory records only. The Python runner does not accept a mass command, iterate over payment credentials, or contact a merchant. Other Python payment behavior remains confined to synthetic local fixtures and the loopback lab.
