namespace php Authority

struct Search
{
    1:i32                  page
    2:i32                  pagesize
    3:list<Condition>      conditions
}

struct Condition
{
    1:string               field
    2:string               expr
    3:string               value
}

struct User
{
    1:i32                  id
    2:string               username
    3:string               nickname
    4:string               password
    5:string               email
    6:string               telephone
}

struct CommonRet
{
    1:i32                  ret
    2:string               data
}

struct Rule
{
    1:i32                  id
    2:string               name
    3:string               data
}

// 功能权限

struct Group
{
    1:i32                  id
    2:i32                  type
    3:string               name
    4:i32                  rule_id
    5:string               description
}

struct Point
{
    1:i32                  id
    2:string               name
    3:string               data
    4:i32                  rule_id
    5:string               description
}

struct Category
{
    1:i32                  id
    2:string               name
    3:i32                  rule_id
    4:string               description
}

struct CategoryPoint
{
    1:i32                  id
    2:string               name
    3:list<Point>          children
    4:string               description
}

struct UserRet
{
    1:i32                  ret
    2:i32                  total
    3:list<User>           users
}

struct UserRlatRet
{
    1:i32                  ret
    2:User                 user
    3:list<string>         super_points
    4:list<string>         points
    5:list<Group>          groups
}

struct AssignableGroupRet
{
    1:i32                  ret
    2:list<Group>          groups
}

struct CategoryRet
{
    1:i32                  ret
    2:i32                  total
    3:list<Category>       categories
}

struct GroupRlatRet
{
    1:i32                  ret
    2:Group                group
    3:Group                parent
    4:list<string>         users
    5:list<i32>            points
}

struct AssignablePointRet
{
    1:i32                  ret
    2:list<CategoryPoint>  points
}

// 资源权限

struct ResourceAttr                             // 资源权限属性
{
    1:i32                  id
    2:string               name
    3:i32                  src_id
    4:i32                  owner_id
    5:i32                  role_id
    6:string               mode
}

struct Role                                     // 角色
{
    1:i32                  id
    2:i32                  type
    3:string               name
}

struct RoleMember                               // 角色成员
{
    1:i32                  id
    2:i32                  role_id
    3:i32                  user_id
}

struct ResourceAttrRet                          // 资源权限列表
{
    1:i32                  ret
    2:i32                  total
    3:list<ResourceAttr>   resource_attrs
}

struct RoleRet                                  // 返回角色列表
{
    1:i32                  ret
    2:i32                  total
    3:list<Role>           roles
}

service AuthorityService
{
    // 功能权限

    CommonRet addRule(1:Rule rule)                                      // 新增规则
    CommonRet rmRule(1:i32 rule_id)                                     // 删除规则

    CommonRet addUser(1:User user)                                      // 新增用户
    CommonRet rmUser(1:i32 user_id)                                     // 删除用户
    CommonRet updateUser(1:i32 user_id, 2:User user)                    // 更新用户信息
    User getUserByName(1:string username)                               // 根据用户名获取单个用户，登录用
    UserRet getUsers(1:Search search)                                   // 获取用户，支持分页
    UserRlatRet getUserById(1:i32 user_id, 2:list<string> rlat)         // 根据ID获取单个用户
    AssignableGroupRet getAssignableGroup(1:i32 user_id)                // 获取用户可分配的组
    bool assignGroup2User(1:list<i32> group_ids, 2:i32 user_id)         // 分配组给用户

    CommonRet addPoint(1:Point point, 2:i32 cate_id)                    // 新增权限点
    CommonRet updatePoint(1:i32 point_id, 2:Point point)                // 更新权限点信息
    CommonRet rmPoint(1:i32 point_id)                                   // 删除权限点

    CommonRet addCategory(1:Category category)                          // 新增权限分类
    CommonRet rmCategory(1:i32 cate_id)                                 // 删除权限分类
    CommonRet updateCategory(1:i32 cate_id, 2:Category category)        // 更新权限分类
    CategoryRet getCategories(1:Search search)                          // 获取所有权限分类

    CommonRet addGroup(1:Group group, 2:i32 parent)                     // 新增普通组(parent = 0)、权限组
    CommonRet rmGroup(1:i32 group_id)                                   // 删除组
    CommonRet updateGroup(1:i32 group_id, 2:Group group)                // 更新组信息
    GroupRlatRet getGroupById(1:i32 group_id, 2:list<string> rlat)      // 获取组关系（父组/用户/权限点）
    AssignablePointRet getAssignablePoint(1:i32 group_id)               // 获取权限组可分配的权限点
    CommonRet assignPoint2Group(1:list<i32> points, 2:i32 group_id)     // 分配权限点给组

    CommonRet addRelation(1:i32 parent, 2:i32 child)                    // 新增关系 权限组-普通组，组-权限点，权限分类-权限点
    CommonRet rmRelation(1:i32 parent, 2:i32 child)                     // 删除关系 同上

    // 资源权限

    CommonRet addResourceAttr(1:ResourceAttr resource_attr)                                 // 新增资源权限属性
    CommonRet rmResourceAttr(1:string name, 2:i32 src_id)                                   // 删除资源权限
    CommonRet updateResourceAttr(1:string name, 2:i32 src_id, 3:ResourceAttr resource_attr) // 更新资源权限
    ResourceAttrRet getResourceAttrs(1:Search search)                                       // 获取资源权限列表

    CommonRet addRole(1:Role role)                                                          // 新增角色
    CommonRet rmRole(1:i32 role_id)                                                         // 删除角色
    CommonRet updateRole(1:i32 role_id, 2:Role role)                                        // 更新角色
    RoleRet getRoles(1:Search search)                                                       // 获取角色列表

    CommonRet addRoleMember(1:i32 role_id, 2:i32 user_id)                                   // 新增角色成员
    CommonRet rmRoleMember(1:i32 role_id, 2:i32 user_id)                                    // 删除角色成员
}
